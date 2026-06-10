const formatters = {
  // 1. MOBILE (PH format example)
  mobile(input) {
    let value = input.value.replace(/\D/g, "").substring(0, 11);

    if (value.length > 7) {
      value = value.replace(/^(\d{4})(\d{3})(\d+)/, "$1 $2 $3");
    } else if (value.length > 4) {
      value = value.replace(/^(\d{4})(\d+)/, "$1 $2");
    }

    input.value = value;
  },

  // 2. CURRENCY (comma separator, optional decimals)
  currency(input) {
    let value = input.value.replace(/,/g, "").replace(/[^\d.]/g, "");

    if (!value) return (input.value = "");

    const number = parseFloat(value);

    if (!isNaN(number)) {
      input.value = number.toLocaleString("en-US", {
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
      });
    }
  },

  // 3. NUMBERS ONLY (no formatting, just clean input)
  number(input) {
    input.value = input.value.replace(/\D/g, "");
  },

  // 4. DECIMAL ONLY (limit to 2 decimal places)
  decimal(input) {
    let value = input.value.replace(/[^0-9.]/g, "");

    const parts = value.split(".");
    if (parts.length > 2) {
      value = parts[0] + "." + parts.slice(1).join("");
    }

    if (parts[1]) {
      parts[1] = parts[1].substring(0, 2);
      value = parts[0] + "." + parts[1];
    }

    input.value = value;
  },

  // 5. PERCENT (0–100)
  percent(input) {
    let value = input.value.replace(/\D/g, "");

    if (value === "") return (input.value = "");

    let num = Math.min(100, parseInt(value));

    input.value = num + "%";
  },

  // 6. CREDIT CARD (XXXX XXXX XXXX XXXX)
  card(input) {
    let value = input.value.replace(/\D/g, "").substring(0, 16);

    value = value.match(/.{1,4}/g);
    input.value = value ? value.join(" ") : "";
  },

  // 7. ZIP CODE (PH or US flexible 4–6 digits)
  zipcode(input) {
    input.value = input.value.replace(/\D/g, "").substring(0, 6);
  },

  // 8. DATE (MM/DD/YYYY simple formatter)
  date(input) {
    let value = input.value.replace(/\D/g, "").substring(0, 8);

    if (value.length > 4) {
      value = value.replace(/^(\d{2})(\d{2})(\d+)/, "$1/$2/$3");
    } else if (value.length > 2) {
      value = value.replace(/^(\d{2})(\d+)/, "$1/$2");
    }

    input.value = value;
  },
};

document.addEventListener("input", (e) => {
  for (const key in formatters) {
    if (e.target.classList.contains(`format-${key}`)) {
      formatters[key](e.target);
    }
  }
});
/*
<input class="format-mobile" placeholder="Mobile">
<input class="format-currency" placeholder="Amount">
<input class="format-number" placeholder="Numbers only">
<input class="format-decimal" placeholder="Decimal">
<input class="format-percent" placeholder="Percent">
<input class="format-card" placeholder="Card number">
<input class="format-zipcode" placeholder="Zip code">
<input class="format-date" placeholder="MM/DD/YYYY"></input> */
