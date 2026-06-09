window.initDataTables = function initDataTables(rows = 10) {
  $("table").each(function () {
    const table = this;

    const dtExists = $.fn.DataTable.isDataTable(table);

    // INIT if not exists
    if (!dtExists) {
      const dt = $(table).DataTable({
        paging: true,
        searching: true,
        info: false,
        lengthChange: false,
        scrollY: "550px",
        scrollCollapse: true,
        pageLength: rows,
        scrollX: $(window).width() < 1024,
        responsive: true,
        autoWidth: true,
        dom: "<'dt-top'f>" + "<'dt-wrapper't>" + "<'dt-bottom'i p>",
        order: [],
      });

      dt.on("draw", () => {
        setTimeout(() => styleDataTable(table), 0);
      });

      styleDataTable(table);
      return;
    }

    // ✅ FIX: instead of draw-only, force re-sync DOM
    const dt = $(table).DataTable();

    dt.clear(); // remove internal cache
    dt.rows.add($(table).find("tbody tr")); // re-read DOM rows
    dt.draw(false);

    dt.columns.adjust();
    dt.responsive?.recalc?.();

    setTimeout(() => styleDataTable(table), 0);
  });

  function styleDataTable(table) {
    const thead = document.querySelectorAll("table thead tr th");
    thead.forEach((th) => {
      th.className =
        "text-black text-center bg-orange-400 text-white font-white py-1";
    });

    const tbodytr = document.querySelectorAll("table tbody tr");
    tbodytr.forEach((tr) => {
      tr.className =
        "hover:bg-orange-300 bg-white duration-300 text-center cursor-pointer";
    });
    const tbody = document.querySelectorAll("table tbody");
    tbody.forEach((tb) => {
      tb.classList.add("border", "rounded-lg");
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
