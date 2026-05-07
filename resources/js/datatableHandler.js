window.initDataTables = function initDataTables(rows = 10) {
  $("table").each(function () {
    if (!$.fn.DataTable.isDataTable(this)) {
      const dt = $(this).DataTable({
        paging: true,
        searching: true,
        info: false, // hide "Showing X of Y" text
        lengthChange: false,
        scrollY: "550px",
        scrollCollapse: true,
        pageLength: rows,
        scrollX: $(window).width() < 1024, // horizontal scroll only if screen < lg (1024px)
        responsive: true, // allows columns to adjust
        autoWidth: true,
        dom: "<'dt-top'f>" + "<'dt-wrapper't>" + "<'dt-bottom'i p>",
        // Disable initial auto-sort
        order: [],
      });

      dt.on("draw", () => {
        styleDataTable(this);
      });

      // Re-style table
      styleDataTable(this);

      // Adjust horizontal scroll on window resize
      $(window).on("resize", () => {
        dt.settings()[0].oInit.scrollX = $(window).width() < 1024;
        dt.columns.adjust();
      });
    }
  });

  function styleDataTable(table) {
    const thead = document.querySelectorAll("table thead tr th");
    thead.forEach((th) => {
      th.className =
        "text-black text-center bg-orange-400 text-white font-white py-1";
    });

    const tbody = document.querySelectorAll("table tbody tr");
    tbody.forEach((tr) => {
      tr.className = "hover:bg-gray-300 bg-white duration-300 text-center";
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
        "py-2",
        "lg:justify-end",
        "dark:text-white",
      );
    });
    search.forEach((searchWrapper) => {
      //   console.log(searchWrapper);
      searchWrapper.classList.add(
        "bg-white",
        "flex",
        "justify-center",
        "py-2",
        "gap-3",
        "lg:justify-end",
        "dark:text-black",
        "items-center",
      );
    });

    //search box design
    const searchbox = document.querySelectorAll(
      '.dt-search input[type="search"]',
    );
    if (!searchbox) {
      console.log("searchbox not available");
      return;
    }
    searchbox.forEach((sbox) => {
      //remove search box class
      sbox.className = "";
      //   //add new classes
      sbox.className =
        "bg-white rounded-lg text-black border-grey-200 drop-shadow-lg";
    });

    const paginationbutton = document.querySelectorAll(".pagination a");

    if (!paginationbutton) {
      console.log("searchbox not available");
      return;
    }

    paginationbutton.forEach((pagebutton) => {
      //remove search box class
      pagebutton.className = "";
      //   //add new classes
      pagebutton.className =
        " text-black font-semibold aspect-square w-10 flex items-center justify-center border border-gray-200 rounded-md text-white";
      const status = pagebutton.getAttribute("aria-disabled");
      if (!status) {
        pagebutton.classList.remove("bg-orange-200");
        pagebutton.classList.add(
          "hover:bg-orange-600",
          "duration-300",
          "bg-orange-400",
        );
      } else {
        pagebutton.classList.remove("bg-orange-400");
        pagebutton.classList.add("bg-orange-200");
      }
    });

    wrapper.forEach((Wrapper) => {
      //   console.log(Wrapper);
      Wrapper.classList.add("bg-white", "text-black");
    });
  }
};
