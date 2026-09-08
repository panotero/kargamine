import QRCode from "qrcode";

/**
 * Stateless print-label helper shared by Container Inventory's single
 * "Print QR" action and its bulk "Print QR Labels" action. Exposed as a
 * global (rather than page-lifecycle-bound like pierCheckin.js/
 * containerAssignment.js) because it never touches the DOM until called -
 * safe to import once at bundle load regardless of which page is current.
 *
 * The QR encodes the container's own container_no (e.g. "MSCU4417820") -
 * the same value already shown everywhere else in the app, not a new
 * synthetic identifier - so this needs no backend/schema change.
 *
 * @param {{container_no: string, variant_label?: string}[]} items
 */
window.printContainerAssetQrLabels = async function printContainerAssetQrLabels(items) {
  if (!items || !items.length) {
    if (typeof showMessage === "function") {
      showMessage({ status: "warning", title: "Nothing to print" });
    }
    return;
  }

  const cards = await Promise.all(
    items.map(async (item) => {
      const dataUrl = await QRCode.toDataURL(item.container_no, { margin: 1, width: 180 });

      return `
        <div style="display:inline-block;width:200px;margin:12px;text-align:center;page-break-inside:avoid;font-family:sans-serif;">
            <img src="${dataUrl}" style="width:180px;height:180px;">
            <div style="font-weight:600;margin-top:6px;">${item.container_no}</div>
            <div style="font-size:12px;color:#666;">${item.variant_label ?? ""}</div>
        </div>
      `;
    }),
  );

  const win = window.open("", "_blank");
  win.document.write(
    `<!DOCTYPE html><html><head><title>Container QR Labels</title></head><body>${cards.join("")}</body></html>`,
  );
  win.document.close();
  win.focus();
  win.print();
};
