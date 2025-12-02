window.initDatePickers = function () {
  const datepickers = document.querySelectorAll(".datetimepicker");
  if (!datepickers.length) return;

  datepickers.forEach((el) => {
    if (!el._flatpickr) {
      flatpickr(el, {
        dateFormat: "d-m-Y",
        allowInput: false,
      });
    }
  });
};
