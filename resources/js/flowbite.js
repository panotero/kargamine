function initFlowbite() {
  if (typeof window.Flowbite === "undefined") {
    const script = document.createElement("script");
    script.src =
      "https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/flowbite.min.js";
    script.defer = true;
    script.onload = () => {
      console.log("Flowbite JS loaded");
    };
    document.head.appendChild(script);
  } else {
    console.log("Flowbite already loaded");
  }
}

window.initFlowbite = initFlowbite;
