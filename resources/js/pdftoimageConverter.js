import * as pdfjsLib from "pdfjs-dist/legacy/build/pdf";

pdfjsLib.GlobalWorkerOptions.workerSrc = "/js/pdf.worker.min.js";

function buildSlideHTML(imgSrc) {
  return `
    <li class="glide__slide flex items-center justify-center bg-gray-100">
      <img src="${imgSrc}" class="max-h-full w-auto object-contain">
    </li>
  `;
}

function loadSlidesFromArray(slides = []) {
  const slideContainer = document.getElementById("glideSlides");
  const loadingOverlay = document.getElementById("galleryLoading");

  slideContainer.innerHTML = "";
  loadingOverlay.classList.remove("hidden");

  slides.forEach((slideHTML) => {
    slideContainer.insertAdjacentHTML("beforeend", slideHTML);
  });

  initGlide();

  loadingOverlay.classList.add("hidden");
}

let glideInstance = null;

window.initGlide = function initGlide() {
  if (glideInstance) glideInstance.destroy();

  glideInstance = new Glide("#galleryGlide", {
    type: "slider",
    focusAt: "center",
    perView: 1,
    gap: 10,
    hoverpause: true,
  });

  glideInstance.mount();

  document
    .querySelector(".slide-previous")
    .addEventListener("click", () => glideInstance.go("<"));

  document
    .querySelector(".slide-next")
    .addEventListener("click", () => glideInstance.go(">"));
};
async function extractPdfImages(
  pdfUrl,
  scale = 1,
  maxWidth = 800,
  maxHeight = 800
) {
  const pdf = await pdfjsLib.getDocument(pdfUrl).promise;

  const slideElements = [];

  for (let i = 1; i <= pdf.numPages; i++) {
    const page = await pdf.getPage(i);
    const viewport = page.getViewport({ scale });

    // Render original PDF page
    const canvas = document.createElement("canvas");
    const context = canvas.getContext("2d");

    canvas.width = viewport.width;
    canvas.height = viewport.height;

    await page.render({
      canvasContext: context,
      viewport,
    }).promise;

    // 🔽 DOWNSIZE HERE
    const resizedDataUrl = downsizeCanvas(canvas, maxWidth, maxHeight);

    // Build response item
    slideElements.push(buildSlideHTML(resizedDataUrl));
  }

  return slideElements;
}
function downsizeCanvas(sourceCanvas, maxWidth, maxHeight, quality = 0.8) {
  const width = sourceCanvas.width;
  const height = sourceCanvas.height;

  let newWidth = width;
  let newHeight = height;

  // Maintain aspect ratio
  if (width > height) {
    if (width > maxWidth) {
      newHeight = Math.round((height * maxWidth) / width);
      newWidth = maxWidth;
    }
  } else {
    if (height > maxHeight) {
      newWidth = Math.round((width * maxHeight) / height);
      newHeight = maxHeight;
    }
  }

  // Create resized canvas
  const resizeCanvas = document.createElement("canvas");
  resizeCanvas.width = newWidth;
  resizeCanvas.height = newHeight;

  const rctx = resizeCanvas.getContext("2d");
  rctx.drawImage(sourceCanvas, 0, 0, newWidth, newHeight);

  // Return smaller image (JPEG compresses better)
  return resizeCanvas.toDataURL("image/jpeg", quality);
}

window.extractPdfImages = extractPdfImages;
window.loadSlidesFromArray = loadSlidesFromArray;
