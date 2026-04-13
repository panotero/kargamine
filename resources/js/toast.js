function showMessage({
  status = "success",
  title = "",
  message = "",
  duration = 5000,
}) {
  const container = document.getElementById("globalMessageContainer");
  if (!container) return;

  const colors = {
    success: "bg-green-500",
    error: "bg-red-500",
    warning: "bg-yellow-600",
  };
  const bgColor = colors[status] || colors.success;

  // ICONS
  const icons = {
    success: `
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2">
        <circle cx="12" cy="12" r="9"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l3 3 5-6"/>
      </svg>
    `,
    error: `
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2">
        <circle cx="12" cy="12" r="9"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 9l6 6M15 9l-6 6"/>
      </svg>
    `,
    warning: `
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-white" fill="none" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M12 4c.5 0 .9.3 1.2.8l7 12.5c.5.9-.2 2-1.2 2H5c-1 0-1.7-1.1-1.2-2l7-12.5c.3-.5.7-.8 1.2-.8z"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4"/>
        <circle cx="12" cy="16.5" r="0.8" fill="currentColor" stroke="none"/>
      </svg>
    `,
  };

  const toast = document.createElement("div");

  toast.className = `
    flex flex-col items-center justify-center
    h-12 max-w-[48px] rounded-full mt-5
    ${bgColor}
    text-white text-sm font-medium
    overflow-hidden whitespace-nowrap
    opacity-0 -translate-y-6
    transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]
  `;
  const titleContainer = document.createElement("div");
  titleContainer.className =
    "flex items-center justify-center text-white text-sm font-medium opacity-0 p-5";
  // ICON WRAPPER
  const iconWrapper = document.createElement("div");
  iconWrapper.className = "flex-shrink-0";
  iconWrapper.innerHTML = icons[status] || icons.success;

  // TEXT
  const titleSpan = document.createElement("span");
  titleSpan.className = " ml-2 transition-opacity duration-300";
  titleSpan.textContent = title;
  titleContainer.appendChild(iconWrapper);
  titleContainer.appendChild(titleSpan);

  // APPEND (icon first, then text)
  //   toast.appendChild(iconWrapper);
  toast.appendChild(titleContainer);
  container.appendChild(toast);

  // Position
  if (window.innerWidth < 768) {
    container.classList.remove("bottom-4");
    container.classList.add("top-4");
  } else {
    container.classList.remove("top-4");
    container.classList.add("bottom-4");
  }

  // STEP 1: Slide in
  requestAnimationFrame(() => {
    toast.classList.remove("opacity-0", "-translate-y-6");
    toast.classList.add("opacity-100", "translate-y-0");
  });

  // STEP 2: Expand (use max-width instead of auto)
  setTimeout(() => {
    toast.classList.remove("max-w-[48px]");
    toast.classList.add("max-w-xs", "px-4");
  }, 400);

  // STEP 3: Show text
  setTimeout(() => {
    titleContainer.classList.remove("opacity-0");
    titleContainer.classList.add("opacity-100");
  }, 650);
  console.log("message: " + message);

  const messagecontainer = document.createElement("div");
  const messageBox = document.createElement("div");
  messagecontainer.className = `flex flex-col justify-center  max-w-[48px] -translate-y-4 rounded-xl  ${bgColor} transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]`;
  if (message !== "") {
    setTimeout(() => {
      messageBox.className = `opacity-0 text-center text-white font-semibold px-5 py-3 min-w-[250px] transition-all duration-500 ease-in
`;
      messageBox.textContent = message;

      // 1️⃣ Append first
      messagecontainer.appendChild(messageBox);

      // 2️⃣ Set collapsed state
      messagecontainer.style.height = "0";
      messagecontainer.style.opacity = "0";
      messagecontainer.style.overflow = "hidden";

      // 3️⃣ Force reflow & expand to actual height
      requestAnimationFrame(() => {
        messagecontainer.style.height = messageBox.scrollHeight + "px";
        messagecontainer.style.opacity = "1";
      });
      //   toast.after(messagecontainer);
      toast.insertAdjacentElement("afterend", messagecontainer);

      setTimeout(() => {
        messagecontainer.classList.remove("max-w-[48px]");
        messagecontainer.classList.add("max-w-xs", "px-4");
        setTimeout(() => {
          messageBox.classList.remove("opacity-0");
        }, 200);
      }, 400);
    }, 850);
  }
  // STEP 4: Closing sequence
  setTimeout(() => {
    setTimeout(() => {
      messageBox.classList.add("opacity-0");
      setTimeout(() => {
        messagecontainer.classList.add("max-w-[48px]");
        messagecontainer.classList.remove("max-w-xs", "px-4");
      }, 300);

      // 1. Hide text
      setTimeout(() => {
        messagecontainer.style.height = "0";
        messagecontainer.style.opacity = "0";

        messagecontainer.addEventListener(
          "transitionend",
          () => {
            messagecontainer.remove();
          },
          { once: true },
        );

        setTimeout(() => {
          titleContainer.classList.remove("opacity-100");
          titleContainer.classList.add("opacity-0");

          // 2. Collapse back to circle
          setTimeout(() => {
            toast.classList.remove("max-w-xs", "px-4");
            toast.classList.add("max-w-[48px]");

            // 3. POP animation (after collapse completes)
            setTimeout(() => {
              // Stage A: slight grow
              toast.classList.add("scale-110");

              setTimeout(() => {
                // Stage B: snap smaller + fade
                toast.classList.remove("scale-110");
                toast.classList.add("scale-[1%]", "opacity-0");

                // remove after animation
                toast.addEventListener("transitionend", () => toast.remove(), {
                  once: true,
                });
              }, 120);
            }, 600);
          }, 300);
        }, 300);
      }, 300);
    }, 500);
    // must be AFTER collapse finishes
  }, duration);
}

window.showMessage = showMessage;
