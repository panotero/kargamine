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
        // Disable initial auto-sort
        order: [],
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
    }
  });
  function styleDataTable(table) {
    table.querySelectorAll("tbody").forEach((tbody) => {
      tbody.classList.remove(
        "divide-y",
        "divide-gray-200",
        "dark:divide-gray-700",
      );
      tbody.querySelectorAll("tr").forEach((row) => {
        row.classList.remove("even:bg-gray-50", "dark:even:bg-gray-900/50");
        row.classList.add(
          "transition-colors",
          "duration-300",
          "hover:border-white",
          "hover:border-3",
        );
      });
    });
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
        "dark:text-black",
      );
    });
    search.forEach((searchWrapper) => {
      //   console.log(searchWrapper);
      searchWrapper.classList.add(
        "flex",
        "justify-center",
        "p-5",
        "lg:justify-end",
        "dark:text-black",
      );
    });
    wrapper.forEach((Wrapper) => {
      //   console.log(Wrapper);
      Wrapper.classList.add("bg-white", "text-black");
    });
  }
};
