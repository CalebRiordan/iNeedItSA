import { highlightBorderError } from "./utils/priceValidator.js";
import { buildQueryString } from "./utils/queryBuilder.js";
import { validatePrice } from "/js/utils/priceValidator.js";

// Event listeners for category dropdown
export var category = "";

const categoryDropdown = document.querySelector(".category.dropdown");
const dropdownButton = categoryDropdown.querySelector("button");
const options = categoryDropdown.querySelector(".dropdown-options");
export const searchField = document.querySelector(".search-field input");

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

priceDropdown.addEventListener("click", () => {
  showPriceRange();
});

// Ensure valid price range
export const minInput = document.getElementById("input-min");
export const maxInput = document.getElementById("input-max");
export const searchButton = document.querySelector(".search-button");

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

export const getValue = (input) => parseInt(input.value) || 0;
var minValue = 0;
var maxValue = 0;

updateSearchButtonState();

[minInput, maxInput].forEach((input) => {
  input.addEventListener("beforeinput", (e) => {
    validatePrice(input, minInput, maxInput, e);
    setTimeout(updateSearchButtonState, 0);
  });
});

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

maxInput?.addEventListener("blur", function () {
  minValue = getValue(minInput) || 0;
  maxValue = getValue(maxInput) || 0;

  if (maxValue < minValue && maxValue !== 0) {
    hidePriceRange();
    showPriceRange();
    highlightBorderError(maxInput);
  }
});

// Update search button when user types in search field
searchField.addEventListener("input", () => {
  updateSearchButtonState();
});

// Search function
function processSearch() {
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

  if (queryString) {
    window.location.href = `${window.APP_CONFIG.baseUrl}products${queryString}`;
  }
}

// Event listener for search button
searchButton.addEventListener("click", processSearch);

searchField.addEventListener("keydown", function (event) {
  if (event.key === "Enter") processSearch();
});
