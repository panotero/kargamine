document.addEventListener("DOMContentLoaded", () => {
  let lastTouchEnd = 0;

  document.addEventListener("gesturestart", function (e) {
    e.preventDefault();
  });

  document.addEventListener("dblclick", function (e) {
    e.preventDefault();
  });
});
