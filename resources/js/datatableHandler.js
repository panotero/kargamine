window.initDataTables = function initDataTables() {
  $("table").each(function () {
    if (!$.fn.DataTable.isDataTable(this)) {
      const dt = $(this).DataTable({
        paging: true,
        searching: true,
        info: false, // hide "Showing X of Y" text
        lengthChange: false,
        scrollY: "550px",
        scrollCollapse: true,
        pageLength: 10,
        scrollX: $(window).width() < 1024, // horizontal scroll only if screen < lg (1024px)
        responsive: true, // allows columns to adjust
        autoWidth: true,
        dom: "<'dt-top'f>" + "<'dt-wrapper't>" + "<'dt-bottom'i p>",
      });

      // Re-style table
      styleDataTable(this);

      dt.on("draw", () => {
        styleDataTable(this);
      });

      // Adjust horizontal scroll on window resize
      $(window).on("resize", () => {
        dt.settings()[0].oInit.scrollX = $(window).width() < 1024;
        dt.columns.adjust();
      });
    } else {
      styleDataTable();
      return;
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
