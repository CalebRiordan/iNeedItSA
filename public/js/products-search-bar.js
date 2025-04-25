import { buildQueryString } from "./utils/queryBuilder.js";

// Event listeners for category dropdown
var category = "";

const categoryDropdown = document.querySelector(".category.dropdown");
const dropdownButton = categoryDropdown.querySelector("button");
const options = categoryDropdown.querySelector(".dropdown-options");
const searchField = document.querySelector(".search-field input");

categoryDropdown.addEventListener("mouseover", () => {
  options.classList.add("show");
});

categoryDropdown.addEventListener("mouseleave", () => {
  options.classList.remove("show");
});

options.querySelectorAll(".option").forEach((option) => {
  option.addEventListener("click", (e) => {
    e.preventDefault();
    category = option.dataset.value;
    dropdownButton.textContent = option.textContent;
    options.classList.remove("show");
  });
});

// Event Listeners for price dropdown
const priceDropdown = document.querySelector(".price.dropdown");
const priceOptions = priceDropdown.querySelector(".dropdown-options");
let lastOpened = 0;

function hidePriceRange(e = null) {
  if (
    (!e || !priceOptions.contains(e.target)) &&
    Date.now() - lastOpened > 300
  ) {
    priceOptions.classList.remove("show");
    document.removeEventListener("click", hidePriceRange);
  }
}

function showPriceRange() {
  if (!priceOptions.classList.contains("show")) {
    priceOptions.classList.add("show");
    lastOpened = Date.now();
    document.addEventListener("click", hidePriceRange);
  }
}

priceDropdown.addEventListener("click", () => {
  showPriceRange();
});

// Ensure valid price range
const minInput = document.getElementById("input-min");
const maxInput = document.getElementById("input-max");
const searchButton = document.querySelector(".search-button");

function updateSearchButtonState() {
  let minValue = getValue(minInput);
  let maxValue = getValue(maxInput);
  let search = searchField.value;

  if ((maxValue > 0 && minValue > maxValue) || !search) {
    searchButton.classList.add("disabled");
  } else {
    searchButton.classList.remove("disabled");
  }
}

const getValue = (input) => parseInt(input.value) || 0;
var minValue = 0;
var maxValue = 0;

updateSearchButtonState();

[minInput, maxInput].forEach((input) => {
  input.addEventListener("beforeinput", (e) => {
    minValue = getValue(minInput);
    maxValue = getValue(maxInput);
    let start = input.selectionStart;
    let end = input.selectionEnd;
    let nextValue;
    let val = input.value;

    if (e.inputType === "deleteContentBackward") {
      nextValue = val.slice(0, start - 1) + val.slice(end);
    } else if (e.inputType === "deleteContentForward") {
      nextValue = val.slice(0, start) + val.slice(end + 1);
    } else {
      nextValue = val.slice(0, start) + (e.data ?? "") + val.slice(end);

      // Ensure only digits
      if (!/^\d*$/.test(nextValue) || nextValue.length > 7) {
        e.preventDefault();
        return;
      }

      // Remove leading zeroes
      if (val && val.charAt(0) === "0") {
        val = val.slice(1);
      }
    }

    if (input === minInput) {
      maxInput.value = Math.max(maxValue, nextValue);
    }
    resetBorder();
    setTimeout(updateSearchButtonState, 0);
  });
});

// Validate price range maximum input
function resetBorder() {
  maxInput.style.border = "";
  maxInput.removeEventListener("input", resetBorder);
}

maxInput?.addEventListener("blur", function () {
  minValue = getValue(minInput) || 0;
  maxValue = getValue(maxInput) || 0;

  if (maxValue < minValue) {
    hidePriceRange();
    showPriceRange();
    this.style.border = "2px solid var(--error-colour)";
    this.addEventListener("input", resetBorder);
  }
});

// Update search button when user types in search field
searchField.addEventListener("input", () => {
  updateSearchButtonState();
});

// Event listener for search button
searchButton.addEventListener("click", () => {
  if (searchButton.classList.contains("disabled")) return;

  let searchString = encodeURIComponent(searchField.value);
  let minValue = getValue(minInput);
  let maxValue = getValue(maxInput);

  let queryParams = {
    search: searchString,
    category: category,
    minPrice: minValue,
    maxPrice: maxValue,
  };

  let queryString = buildQueryString(queryParams);

  console.log(`${
      window.APP_CONFIG.baseUrl
    }products${buildQueryString(queryParams)}`);
  

  if (queryString) {
    window.location.href = `${
      window.APP_CONFIG.baseUrl
    }products${buildQueryString(queryParams)}`;
  }
});
