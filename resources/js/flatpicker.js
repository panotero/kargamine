window.initDatePickers = function () {
  const datepickers = document.querySelectorAll(".datetimepicker");
  if (!datepickers.length) return;

  const today = new Date();

  datepickers.forEach((el) => {
    if (!el._flatpickr) {
      flatpickr(el, {
        dateFormat: "d-m-Y",
        allowInput: false,
        defaultDate: today,
      });
    }
  });
};
