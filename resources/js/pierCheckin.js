import jsQR from "jsqr";
import QRCode from "qrcode";

(function () {
  const page = document.getElementById("pierCheckinPage");
  if (!page) return;

  let stream = null;
  let scanning = false;
  let submitting = false;
  let pendingUnits = [];

  const video = document.getElementById("pcVideo");
  const canvas = document.getElementById("pcCanvas");
  const ctx = canvas.getContext("2d", { willReadFrequently: true });
  const resultBanner = document.getElementById("pcResultBanner");
  const manualInput = document.getElementById("pcManualCode");
  const manualBtn = document.getElementById("pcManualSubmitBtn");
  const startBtn = document.getElementById("pcStartScanBtn");
  const stopBtn = document.getElementById("pcStopScanBtn");
  const listBody = document.getElementById("pcPendingBody");
  const printBtn = document.getElementById("pcPrintListBtn");

  // -----------------------------------------------------------------
  // Camera scanning
  // -----------------------------------------------------------------
  async function startScanning() {
    try {
      stream = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: "environment" },
      });
    } catch (e) {
      showMessage({
        status: "error",
        title: "Camera unavailable",
        message: "Use manual entry below instead.",
      });
      return;
    }

    video.srcObject = stream;
    video.setAttribute("playsinline", true);
    await video.play();

    scanning = true;
    startBtn.classList.add("hidden");
    stopBtn.classList.remove("hidden");
    requestAnimationFrame(tick);
  }

  function stopScanning() {
    scanning = false;

    if (stream) {
      stream.getTracks().forEach((t) => t.stop());
      stream = null;
    }

    startBtn.classList.remove("hidden");
    stopBtn.classList.add("hidden");
  }

  function tick() {
    if (!scanning) return;

    if (video.readyState === video.HAVE_ENOUGH_DATA) {
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

      const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
      const code = jsQR(imageData.data, imageData.width, imageData.height);

      if (code && code.data) {
        submitCode(code.data);
        return; // submitCode() re-arms the loop once it's done
      }
    }

    requestAnimationFrame(tick);
  }

  // -----------------------------------------------------------------
  // Confirm a scanned/typed code
  // -----------------------------------------------------------------
  async function submitCode(code) {
    if (submitting) return;
    submitting = true;

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload: { code },
      url: "/api/gate-scan",
    });

    if (!response.success) {
      showMessage({ status: "error", title: "Scan failed", message: response.message ?? "" });
      renderBanner(false, response.message ?? "Unknown container code.");
    } else {
      const unit = response.data;
      const label = response.action === "OUT" ? "Gated OUT" : "Gated IN";
      showMessage({ status: "success", title: label });
      renderBanner(
        true,
        `${label}: ${unit.container_asset?.container_no ?? code} — ${unit.booking?.code ?? ""}`,
      );
      loadPending();
    }

    submitting = false;
    if (scanning) requestAnimationFrame(tick);
  }

  function renderBanner(success, message) {
    resultBanner.textContent = message;
    resultBanner.classList.remove(
      "hidden",
      "bg-emerald-50",
      "text-emerald-700",
      "bg-red-50",
      "text-red-700",
    );
    resultBanner.classList.add(
      success ? "bg-emerald-50" : "bg-red-50",
      success ? "text-emerald-700" : "text-red-700",
    );
  }

  manualBtn.addEventListener("click", function () {
    const code = manualInput.value.trim();
    if (!code) return;
    submitCode(code);
    manualInput.value = "";
  });

  manualInput.addEventListener("keydown", function (e) {
    if (e.key === "Enter") {
      e.preventDefault();
      manualBtn.click();
    }
  });

  startBtn.addEventListener("click", startScanning);
  stopBtn.addEventListener("click", stopScanning);

  // -----------------------------------------------------------------
  // Pending list + print
  // -----------------------------------------------------------------
  async function loadPending() {
    const response = await apiCall({ mode: "GET", url: "/api/gate-scan/pending" });
    if (!response.success) return;

    pendingUnits = response.data ?? [];
    renderPendingList();
  }

  function renderPendingList() {
    if (!pendingUnits.length) {
      listBody.innerHTML =
        '<tr><td colspan="4" class="px-3 py-4 text-center text-zinc-400">Nothing pending.</td></tr>';
      return;
    }

    listBody.innerHTML = pendingUnits
      .map((unit) => {
        const action = unit.actual_gate_out_at ? "IN" : "OUT";
        const badgeClasses =
          action === "OUT" ? "bg-orange-50 text-orange-700" : "bg-blue-50 text-blue-700";
        const route = `${unit.booking_line?.origin_port?.code ?? "-"} → ${unit.booking_line?.destination_port?.code ?? "-"}`;

        return `
          <tr>
              <td class="px-3 py-2">${unit.booking?.code ?? "-"}</td>
              <td class="px-3 py-2">${unit.container_asset?.container_no ?? "-"}</td>
              <td class="px-3 py-2">${route}</td>
              <td class="px-3 py-2"><span class="inline-flex items-center rounded-full ${badgeClasses} px-2 py-0.5 text-xs font-medium">Gate ${action}</span></td>
          </tr>
        `;
      })
      .join("");
  }

  async function printList() {
    if (!pendingUnits.length) {
      showMessage({ status: "warning", title: "Nothing to print" });
      return;
    }

    const cards = await Promise.all(
      pendingUnits.map(async (unit) => {
        const dataUrl = await QRCode.toDataURL(unit.gate_pass_code, { margin: 1, width: 180 });
        const action = unit.actual_gate_out_at ? "Gate IN" : "Gate OUT";

        return `
          <div style="display:inline-block;width:200px;margin:12px;text-align:center;page-break-inside:avoid;font-family:sans-serif;">
              <img src="${dataUrl}" style="width:180px;height:180px;">
              <div style="font-weight:600;margin-top:6px;">${unit.container_asset?.container_no ?? "-"}</div>
              <div style="font-size:12px;color:#666;">${unit.booking?.code ?? "-"} — ${action}</div>
              <div style="font-size:11px;color:#999;">${unit.gate_pass_code}</div>
          </div>
        `;
      }),
    );

    const win = window.open("", "_blank");
    win.document.write(
      `<!DOCTYPE html><html><head><title>Gate Scan List</title></head><body>${cards.join("")}</body></html>`,
    );
    win.document.close();
    win.focus();
    win.print();
  }

  printBtn.addEventListener("click", printList);

  loadPending();
})();
