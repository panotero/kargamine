import * as pdfjsLib from "pdfjs-dist/legacy/build/pdf";

pdfjsLib.GlobalWorkerOptions.workerSrc = "/js/pdf.worker.min.js";
//backup for glider
// function buildSlideHTML(imgSrc) {
//   return `
//     <li class="glide__slide flex items-center justify-center bg-gray-100">
//       <img src="${imgSrc}" class="max-h-full w-auto object-contain">
//     </li>
//   `;
// }

//function for swiper
function buildSlideHTML(imgSrc) {
  return `
    <div class="swiper-slide flex items-center justify-center bg-gray-100">
      <div class="swiper-zoom-container">
        <img src="${imgSrc}" class="max-h-full w-auto object-contain">
      </div>
    </div>
  `;
}

function loadSlidesFromArray(slides = []) {
  //containter for glide
  //   const slideContainer = document.getElementById("glideSlides");

  //container for swiper
  const slideContainer = document.getElementById("swiperSlides");

  const loadingOverlay = document.getElementById("galleryLoading");

  slideContainer.innerHTML = "";
  //   loadingOverlay.classList.remove("hidden");

  slides.forEach((slideHTML) => {
    slideContainer.insertAdjacentHTML("beforeend", slideHTML);
  });
  //   loadingOverlay.classList.add("hidden");

  initSwiper();
}

// window.initSwiper = function initSwiper() {
//   if (swiperInstance) swiperInstance.destroy();

//   swiperInstance = new Glide("#swiperSlides", {
//     type: "slider",
//     focusAt: "center",
//     perView: 1,
//     gap: 10,
//     hoverpause: true,
//   });

//   swiperInstance.mount();

//   document
//     .querySelector(".slide-previous")
//     .addEventListener("click", () => swiperInstance.go("<"));

//   document
//     .querySelector(".slide-next")
//     .addEventListener("click", () => swiperInstance.go(">"));
// };
let swiperInstance = null;

window.initSwiper = function initSwiper() {
  if (swiperInstance) {
    swiperInstance.destroy(true, true);
  }

  swiperInstance = new Swiper("#gallerySwiper", {
    modules: [Navigation, Pagination, Zoom], //  CORRECT
    slidesPerView: 1,
    spaceBetween: 10,
    zoom: {
      maxRatio: 3,
    },
    grabCursor: true,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".slide-next",
      prevEl: ".slide-previous",
    },
  });
};

async function extractPdfImages(
  pdfUrl,
  scale = 1,
  maxWidth = 800,
  maxHeight = 800,
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
