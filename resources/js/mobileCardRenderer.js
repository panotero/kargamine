class TableToCard {
  constructor({ tableId, cardContainerId, mobileBreakpoint = 768 }) {
    this.table = document.getElementById(tableId);
    this.cardContainer = document.getElementById(cardContainerId);
    this.mobileBreakpoint = mobileBreakpoint;

    if (!this.table || !this.cardContainer) {
      console.warn(
        `❗ Table or card container not found: ${tableId}, ${cardContainerId}`
      );
      return;
    }

    this.init();
    this.addResizeListener();
  }

  init() {
    this.renderCards();
  }

  renderCards() {
    if (window.innerWidth <= this.mobileBreakpoint) {
      this.cardContainer.innerHTML = "";
      this.table.classList.add("hidden");

      const rows = Array.from(this.table.querySelectorAll("tbody tr"));
      const headers = Array.from(this.table.querySelectorAll("thead th")).map(
        (th) => th.textContent
      );

      rows.forEach((row) => {
        const cells = row.querySelectorAll("td");
        if (cells.length === 0) return;

        const card = document.createElement("div");
        card.classList.add(
          "bg-white",
          "rounded-xl",
          "p-4",
          "shadow-md",
          "flex",
          "flex-col",
          "gap-2"
        );

        cells.forEach((cell, index) => {
          if (index < headers.length - 1) {
            const field = document.createElement("p");
            field.innerHTML = `<strong>${headers[index]}:</strong> ${cell.textContent}`;
            card.appendChild(field);
          }
        });

        const actionsCell = cells[cells.length - 1];
        if (actionsCell) {
          const actions = document.createElement("div");
          actions.classList.add("flex", "gap-2", "justify-end");
          actions.innerHTML = actionsCell.innerHTML;
          card.appendChild(actions);
        }

        this.cardContainer.appendChild(card);
      });

      this.cardContainer.classList.remove("hidden");
    } else {
      this.table.classList.remove("hidden");
      this.cardContainer.classList.add("hidden");
    }
  }

  addResizeListener() {
    window.addEventListener("resize", () => this.renderCards());
  }

  refresh() {
    this.renderCards();
  }
}
