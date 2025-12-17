window.initDataTables = function initDataTables() {
  $("table").each(function () {
    if (!$.fn.DataTable.isDataTable(this)) {
      const dt = $(this).DataTable({
        paging: true,
        searching: true,
        info: true,
        lengthChange: false, // remove "Show X entries"
        scrollY: "250px", // table body height
        scrollCollapse: true,
        scrollX: true, // allow horizontal scroll
        dom: "<'dt-top'f>" + "<'dt-wrapper't>" + "<'dt-bottom'i p>",
        responsive: true,
        autoWidth: false,
      });

      // Initial styling
      styleDataTable(this);

      // Re-style after every draw (paging, search, sort, etc.)
      dt.on("draw", () => {
        styleDataTable(this);
      });
    }
  });
  function styleDataTable(table) {
    const pagination = document.querySelectorAll(".pagination");
    // console.log(pagination);
    const search = document.querySelectorAll(".dt-search");
    const wrapper = document.querySelectorAll(".dt-wrapper");
    pagination.forEach((paginationWrapper) => {
      //   console.log(paginationWrapper);
      paginationWrapper.classList.add(
        "flex",
        "justify-center",
        "p-5",
        "lg:justify-end",
        "dark:text-white"
      );
    });
    search.forEach((searchWrapper) => {
      //   console.log(searchWrapper);
      searchWrapper.classList.add(
        "flex",
        "justify-center",
        "p-5",
        "lg:justify-end",
        "dark:text-white"
      );
    });
    wrapper.forEach((Wrapper) => {
      //   console.log(Wrapper);
      Wrapper.classList.add("bg-white", "text-black");
    });
  }
};
