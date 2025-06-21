import {
    highlightBorderError,
    validatePrice,
} from "/js/utils/priceValidator.js";
import { buildQueryString } from "/js/utils/queryBuilder.js";

export var category = "";

const categoryDropdown = document.querySelector(".category.dropdown");
const dropdownButton = categoryDropdown.querySelector("button");
const options = categoryDropdown.querySelector(".dropdown-options");
export const searchField = document.querySelector(".search-field input");

// Price Dropdown
const priceDropdown = document.querySelector(".price.dropdown");
const priceOptions = priceDropdown.querySelector(".dropdown-options");
let lastOpened = 0;

// Get elements and variables used to ensure valid price range
export const minInput = document.getElementById("input-min");
export const maxInput = document.getElementById("input-max");
export const getValue = (input) => parseInt(input.value) || 0;
var minValue = 0;
var maxValue = 0;

export const searchButton = document.querySelector(".search-button");

// ============== FUNCTIONS =============
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

function hidePriceRange(e = null) {
  // Close price range selector when user clicks something else
    if (
        (!e || !priceOptions.contains(e.target)) &&
        Date.now() - lastOpened > 300
    ) {
        priceOptions.classList.remove("show");
        document.removeEventListener("click", hidePriceRange);
    }
}

function showPriceRange() {
  // Open price range selector when use clicks price dropdown
    if (!priceOptions.classList.contains("show")) {
        priceOptions.classList.add("show");
        lastOpened = Date.now();
        document.addEventListener("click", hidePriceRange);
    }
}

// Search function
function processSearch() {
    if (searchButton.classList.contains("disabled")) return;

    let searchString = searchField.value;
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

// ============== EVENT LISTENERS ==============
// Category Dropdown
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

// Price Dropdown
priceDropdown.addEventListener("click", () => {
    showPriceRange();
});

[minInput, maxInput].forEach((input) => {
    input.addEventListener("beforeinput", (e) => {
        validatePrice(input, minInput, maxInput, e);
        setTimeout(updateSearchButtonState, 0);
    });
});

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

// Event listener for search button submission
searchButton.addEventListener("click", processSearch);

searchField.addEventListener("keydown", function (event) {
    if (event.key === "Enter") processSearch();
});

// Initial state
updateSearchButtonState();
