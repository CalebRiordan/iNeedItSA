import { buildQueryString } from "../utils/queryBuilder.js";
import {
  validatePrice,
  highlightBorderError,
} from "/js/utils/priceValidator.js";

const content = document.querySelector(".products-content-wrapper");
const params = JSON.parse(content.getAttribute("data-params"));

const searchField = document.querySelector(".filter-panel .search-field input");
const searchButton = document.querySelector(".filter-panel .search-button");
const stars = document.querySelectorAll(".star-rating .star");
const minInput = document.getElementById("input-min");
const maxInput = document.getElementById("input-max");
const resultLabel = document.querySelector(".products-catalogue h1 span");
const productsCatalogue = document.querySelector(".products-catalogue");

let minPrice = params["maxPrice"] ?? null;
let maxPrice = params["maxPrice"] ?? null;
let category = params["category"] ?? null;
let rating = params["rating"] ?? null;

const getValue = (input) => parseInt(input.value) || 0;

// EVENT LISTENERS

// Category List
document.querySelectorAll(".category-list .option").forEach((option) => {
  option.addEventListener("click", function () {
    document.querySelectorAll(".category-list .option").forEach((opt) => {
      opt.classList.remove("active");
    });
    this.classList.add("active");
    category = this.dataset.value;
  });
});

// Rating Stars
stars.forEach((star) => {
  star.addEventListener("click", () => {
    setRating(star.dataset.value);
  });
});

// Min- and max price inputs
[minInput, maxInput].forEach((input) => {
  input.addEventListener("beforeinput", (e) => {
    validatePrice(input, minInput, maxInput, e);
  });
});

maxInput?.addEventListener("blur", function () {
  minPrice = getValue(minInput);
  maxPrice = getValue(maxInput);

  if (maxPrice < minPrice && maxPrice !== 0) {
    highlightBorderError(maxInput);
  }
});

// Process Search
searchButton.addEventListener("click", processSearch);
searchField.addEventListener("keydown", function (event) {
  if (event.key === "Enter") processSearch();
});

function setRating(number) {
  rating = number;
  number ??= 1;
  stars.forEach((star, index) => {
    const starWrapper = star.parentElement;
    starWrapper.classList.toggle("excluded", index < number - 1);
  });

  let label = "";

  switch (Number(number)) {
    case 1:
      break;
    case 5:
      label = `5 stars only`;
      break;
    default:
      label = `${number} stars and up`;
      break;
  }

  document.getElementById("rating-label").textContent = label;
}

function applyParams() {
  minPrice = getValue(minInput);
  maxPrice = getValue(maxInput);

  Object.assign(params, {
    minPrice,
    maxPrice,
    category,
    rating,
  });

  let queryString = getQueryString();
  refreshPartials(queryString);
  history.replaceState(null, "", `/products${queryString}`);
}

function processSearch() {
  applyParams()
  let queryString = getQueryString();

  if (queryString) {
    refreshPartials(queryString);
    resultLabel.textContent = `\"${encodeURIComponent(searchField.value)}\"`;
    history.replaceState(null, "", `/products${queryString}`)
  }
}

function setPageEventListeners() {
  document.querySelectorAll(".page-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const page = this.getAttribute("data-page");
      if (page !== params["page"]) {
        setPage(page);
        refreshPartials(getQueryString());
      }
    });
  });
}

function setPage(page = 1) {
  params["page"] = page;
}

function getQueryString() {
  // use params
  let queryParams = {
    search: encodeURIComponent(searchField.value),
    category: params["category"] ?? null,
    minPrice: params["minPrice"] ?? null,
    maxPrice: params["maxPrice"] ?? null,
    rating: params["rating"] ?? null,
    page: params["page"] ?? null,
  };
  
  return buildQueryString(queryParams);
}

function refreshPartials(queryString) {  
  fetch(`/partial/products-display${queryString}`)
    .then((response) => response.json())
    .then((data) => {
      
      if (data['products-display'] !== ""){
        // Replace existing HTML
        document.querySelector(".products-grid").innerHTML =
          data["products-display"];
        document.querySelector(".page-selector").innerHTML =
          data["page-selector"];
          productsCatalogue.classList.remove('empty');
      } else {
        productsCatalogue.classList.add('empty');
        document.querySelector(".page-selector").innerHTML = ""
      }

      window.scrollTo(0, 0);

      // Update page-selector
      setPageEventListeners();
    });
}

// Initial setup
setPageEventListeners();
setRating(rating);
setPage();

if (params['products-display'] === ""){
  productsCatalogue.classList.add('empty');
  document.querySelector(".page-selector").innerHTML = ""
}