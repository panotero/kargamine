document.addEventListener("DOMContentLoaded", () => {
  let lastTouchEnd = 0;

  // resources/js/disableZoom.js
  document.addEventListener("gesturestart", function (e) {
    e.preventDefault(); //block pinch zoom
  });

  document.addEventListener("dblclick", function (e) {
    e.preventDefault(); //block double-tap zoom
  });
});
window.initDataTables = function initDataTables() {
  document.querySelectorAll("table").forEach((table) => {
    if (!$.fn.dataTable.isDataTable(table)) {
      $(table).DataTable({
        paging: true,
        searching: true,
        info: true,
        responsive: true,
        dom: "Bfrtip", // include buttons & search input
        buttons: [
          "excel", // Excel export
          "pdf", // PDF export
        ],
        stripeClasses: [""],
        language: {
          search: "",
          searchPlaceholder: "Search...",
          info: '<span class="dark:text-white">Showing _START_ to _END_ of _TOTAL_ entries</span>',
        },
        initComplete: function () {
          const tableEl = this;

          // Style table header
          $(tableEl).find("thead th").css({
            "background-color": "#f8f8f8",
            color: "#1c1c1c",
            "font-weight": "500",
            "border-bottom": "2px solid #e0e0e0",
            padding: "12px 10px",
            "text-align": "left",
          });

          // Style search input (white and rounded)
          $('div.dataTables_filter input[type="search"]').css({
            "background-color": "#ffffff",
            border: "1px solid #d0d0d0",
            "border-radius": "12px",
            padding: "6px 12px",
            marginBottom: "5px",
            width: "200px",
            color: "#1c1c1c",
            "box-shadow": "0 1px 3px rgba(0,0,0,0.1)",
            outline: "none",
          });

          // Make the whole table rounded
          $(tableEl).css({
            "border-radius": "12px",
            overflow: "hidden",
            border: "1px solid #e0e0e0",
          });

          // Apply pagination styling initially
          stylePagination(tableEl);
        },
        drawCallback: function () {
          // Reapply pagination styling on every redraw
          stylePagination(this);
        },
      });
    }

    function stylePagination(table) {
      // Button styles
      const buttonStyle = {
        "background-color": "#ffffff",
        border: "1px solid #d0d0d0",
        color: "#1c1c1c",
        "border-radius": "50%",
        width: "2.5rem",
        height: "2.5rem",
        "line-height": "2.5rem",
        "text-align": "center",
        "box-shadow": "0 1px 3px rgba(0,0,0,0.1)",
        cursor: "pointer",
        display: "flex",
        "align-items": "center",
        "justify-content": "center",
      };

      const currentButtonStyle = {
        "background-color": "#ffffff",
        border: "1px solid #d0d0d0",
        "box-shadow": "0 2px 4px rgba(0,0,0,0.2)",
        "font-weight": "bold",
        color: "#000",
        cursor: "default",
      };

      const paginate = $(table)
        .closest(".dataTables_wrapper")
        .find(".dataTables_paginate");

      paginate
        .find("a.paginate_button.previous, a.paginate_button.next")
        .remove();

      // Flex container
      paginate.css({
        display: "flex",
        justifyContent: "center",
        alignItems: "center",
        gap: "0.5rem",
        marginTop: "1rem",
      });

      const dt = $(table).DataTable();
      const info = dt.page.info();
      const totalPages = info.pages;
      const currentPage = info.page + 1; // 1-based
      const maxButtons = 3;

      let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
      let endPage = startPage + maxButtons - 1;
      if (endPage > totalPages) {
        endPage = totalPages;
        startPage = Math.max(1, endPage - maxButtons + 1);
      }

      // Clear previous numeric buttons
      paginate.find("span").remove();

      const span = $("<span></span>").css({
        display: "flex",
        gap: "0.5rem",
        alignItems: "center",
      });

      // First page button
      const firstBtn = $('<a class="paginate_button first" href="#">«</a>').css(
        buttonStyle
      );
      firstBtn.toggleClass("disabled", currentPage === 1).click(function (e) {
        e.preventDefault();
        if (currentPage !== 1) dt.page("first").draw("page");
      });
      span.append(firstBtn);

      // Previous button (custom SVG)
      const prevBtn = $(
        '<a class="paginate_button previous" href="#"></a>'
      ).css(buttonStyle);
      prevBtn.html(`<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24">
    <path d="M15 18l-6-6 6-6" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>`);
      prevBtn.toggleClass("disabled", currentPage === 1).click(function (e) {
        e.preventDefault();
        if (currentPage !== 1) dt.page("previous").draw("page");
      });
      span.append(prevBtn);

      // Ellipsis before
      if (startPage > 1) {
        span.append(
          '<span class="dark:text-white" style="display:flex;align-items:center;">…</span>'
        );
      }

      // Numeric buttons
      for (let i = startPage; i <= endPage; i++) {
        const btn = $(
          `<a class="paginate_button ${
            i === currentPage ? "current" : ""
          }" href="#">${i}</a>`
        ).css(buttonStyle);
        if (i === currentPage) btn.css(currentButtonStyle);
        btn.click(function (e) {
          e.preventDefault();
          dt.page(i - 1).draw("page");
        });
        span.append(btn);
      }

      // Ellipsis after
      if (endPage < totalPages) {
        span.append(
          '<span class="dark:text-white" style="display:flex;align-items:center;">…</span>'
        );
      }

      // Next button (custom SVG)
      const nextBtn = $('<a class="paginate_button next" href="#"></a>').css(
        buttonStyle
      );
      nextBtn.html(`<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24">
    <path d="M9 18l6-6-6-6" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>`);
      nextBtn
        .toggleClass("disabled", currentPage === totalPages)
        .click(function (e) {
          e.preventDefault();
          if (currentPage !== totalPages) dt.page("next").draw("page");
        });
      span.append(nextBtn);

      // Last page button
      const lastBtn = $('<a class="paginate_button last" href="#">»</a>').css(
        buttonStyle
      );
      lastBtn
        .toggleClass("disabled", currentPage === totalPages)
        .click(function (e) {
          e.preventDefault();
          if (currentPage !== totalPages) dt.page("last").draw("page");
        });
      span.append(lastBtn);

      paginate.append(span);
    }
  });
};
