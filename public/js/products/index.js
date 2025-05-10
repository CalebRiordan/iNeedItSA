import { buildQueryString } from "../utils/queryBuilder.js";
import {
  validatePrice,
  highlightBorderError,
} from "/js/utils/priceValidator.js";

const content = document.querySelector(".products-content-wrapper")
const params = JSON.parse(content.getAttribute("data-params"));

const searchField = document.querySelector(".filter-panel .search-field input");
const searchButton = document.querySelector(".filter-panel .search-button");
const filterButton = document.querySelector(".filter-panel .apply-filter-btn");
const stars = document.querySelectorAll(".star-rating .star");
const minInput = document.getElementById("input-min");
const maxInput = document.getElementById("input-max");
const leftArrow = document.querySelector(".page-arrow-btn.left");
const rightArrow = document.querySelector(".page-arrow-btn.right");
const currentPage = document.getElementById("current-page");

const getValue = (input) => parseInt(input.value) || 0;
var minValue = 0;
var maxValue = 0;
let category = params['cateogry'] ?? null;

// EVENT LISTENERS

// Category List
document.querySelectorAll(".category-list .option").forEach((option) => {
  option.addEventListener("click", function () {
    document.querySelectorAll(".category-list .option").forEach((opt) => {
      opt.classList.remove("active");
    });
    this.classList.add("active");
    category =  this.dataset.value;
  });
});

// Rating Stars
stars.forEach((star) => {
  star.addEventListener("click", () => setRating(star.dataset.value));
});

// Min- and max price inputs
[minInput, maxInput].forEach((input) => {
  input.addEventListener("beforeinput", (e) => {
    validatePrice(input, minInput, maxInput, e);
    setTimeout(updateFilterBtnState, 0);
  });
});

maxInput?.addEventListener("blur", function () {
  minValue = getValue(minInput);
  maxValue = getValue(maxInput);

  if (maxValue < minValue && maxValue !== 0) {
    highlightBorderError(maxInput);
  }
});

// Page Selector
document.querySelectorAll(".page-btn").forEach((button) => {
  button.addEventListener("click", function () {
    const page = this.getAttribute("data-page");
    if (page !== currentPage.value){
      document.getElementById("current-page").value = page;
  
      fetchProducts(page);
    }
  });
});

// Process Search
filterButton.addEventListener("click", processSearch);
searchButton.addEventListener("click", processSearch);
searchField.addEventListener("keydown", function (event) {
  if (event.key === "Enter") processSearch();
});

function setRating(rating) {
  stars.forEach((star, index) => {
    const starWrapper = star.parentElement;
    starWrapper.classList.toggle("excluded", index < rating - 1);
  });
  document.getElementById("rating").value = rating;

  let label = "";

  switch (Number(rating)) {
    case 1:
      break;
    case 5:
      label = `${rating} stars only`;
      break;
    default:
      label = `${rating} stars and up`;
      break;
  }

  document.getElementById("rating-label").textContent = label;
}

function updateFilterBtnState() {
  let minValue = getValue(minInput);
  let maxValue = getValue(maxInput);
  let search = searchField.value;

  if ((maxValue > 0 && minValue > maxValue) || !search) {
    filterButton.classList.add("disabled");
  } else {
    filterButton.classList.remove("disabled");
  }
}

function processSearch() {
  let searchString = encodeURIComponent(searchField.value);
  let minValue = getValue(minInput);
  let maxValue = getValue(maxInput);
  let rating = document.getElementById("rating").value;

  let queryParams = {
    search: searchString,
    category: category,
    minPrice: minValue,
    maxPrice: maxValue,
    rating: rating
  };

  let queryString = buildQueryString(queryParams);

  if (queryString) {
    window.location.href = `${window.APP_CONFIG.baseUrl}products${queryString}`;
    // AJAX
  }
}

function updateArrows() {
  leftArrow.style.display = currentPage.value > 1 ? "block" : "none";

  rightArrow.style.display =
    inner.scrollLeft + inner.offsetWidth < inner.scrollWidth - 5
      ? "block"
      : "none";
}

// Initial states
updateFilterBtnState();
setRating(document.getElementById("rating").value);
updateArrows();
