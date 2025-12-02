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

async function extractPdfImages(pdfUrl, scale = 1) {
  const pdf = await pdfjsLib.getDocument(pdfUrl).promise;

  const slideElements = [];

  for (let i = 1; i <= pdf.numPages; i++) {
    const page = await pdf.getPage(i);
    const viewport = page.getViewport({ scale });

    const canvas = document.createElement("canvas");
    const context = canvas.getContext("2d");

    canvas.width = viewport.width;
    canvas.height = viewport.height;

    await page.render({
      canvasContext: context,
      viewport,
    }).promise;

    const imgSrc = canvas.toDataURL("image/png");

    slideElements.push(buildSlideHTML(imgSrc));
  }

  return slideElements;
}

window.extractPdfImages = extractPdfImages;
window.loadSlidesFromArray = loadSlidesFromArray;
