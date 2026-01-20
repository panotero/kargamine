function initFlowbite() {
  if (typeof window.Flowbite === "undefined") {
    const script = document.createElement("script");
    script.src = "https://cdn.jsdelivr.net/npm/@glidejs/glide";
    script.defer = true;
    script.onload = () => {
      //   console.log("Flowbite JS loaded");
    };
    document.head.appendChild(script);
  } else {
    // console.log("Flowbite already loaded");
  }
}

initFlowbite();
const slidesContainer = document.getElementById("pdfSlide");

try {
  const imageArray = [];
  listings.forEach((listing) => {
    const imageList = JSON.parse(listing.image_links);
    const imageSlides = imageList
      .map(
        (img) => `
        <li class="glide__slide">
  <img
    src="${img}"
    alt="${listing.property_name}"
    class="rounded-lg w-full max-h-96 object-contain"
  />
</li>
      `,
      )
      .join("");
    let num = listing.price;
    price = num;
    const slideHTML = `
        <li class="glide__slide">
  <div class=" h-full w-full flex flex-col lg:flex-row bg-white rounded-2xl shadow-lg overflow-hidden md:p-8 p-4">

    <!-- Property Info -->
    <div class="lg:w-1/2 flex flex-col justify-center text-center lg:text-left lg:pr-8">
      <div class="space-y-4">
        <div>
          <h1 class="text-sm md:text-base font-semibold text-gray-400 uppercase tracking-wide">Address</h1>
          <h1 class="text-2xl md:text-4xl font-bold text-gray-800 leading-snug">${listing.address}</h1>
        </div>

        <div>
          <h1 class="text-sm md:text-base font-semibold text-gray-400 uppercase tracking-wide">Price</h1>
          <h1 class="text-2xl md:text-4xl font-extrabold text-green-600">$${price}</h1>
        </div>

        <p class="hidden md:block text-gray-500 text-base max-h-32 overflow-y-auto leading-relaxed">
          ${listing.description}
        </p>

        <div class="pt-3">
          <a
            href="${listing.link}"
            target="_blank"
            class="inline-block bg-blue-700 hover:bg-blue-800 text-white font-medium md:text-lg text-base px-6 py-3 rounded-full shadow-md border border-blue-900 transition-all duration-200 hover:scale-[1.03]"
          >
            See Full Details
          </a>
        </div>
      </div>
    </div>


  </div>
</li>

      `;
    slidesContainer.insertAdjacentHTML("beforeend", slideHTML);
  });
} catch (error) {
  console.error(error);
}

const parentGlide = new Glide(".glide-parent", {
  type: "carousel",
  autoplay: 2000,
  hoverpause: true,
});
document.querySelector(".parent-prev").addEventListener("click", (event) => {
  event.stopPropagation();
  parentGlide.go("<");
});
document.querySelector(".parent-next").addEventListener("click", (event) => {
  event.stopPropagation();
  parentGlide.go(">");
});
parentGlide.mount();
