import { buildQueryString } from "/js/utils/queryBuilder.js";
import {
    validatePrice,
    highlightBorderError,
} from "/js/utils/priceValidator.js";
import { resizeProductCardTitles } from "/js/utils/scaleProductCardFont.js";

const content = document.querySelector(".products-content-wrapper");
const params = JSON.parse(content.getAttribute("data-params"));

const searchField = document.querySelector(".filter-panel .search-field input");
const searchButton = document.querySelector(".filter-panel .search-button");
const stars = document.querySelectorAll(".star-rating .star");
const filterButton = document.querySelector(".filter-panel .apply-filter-btn");
const minInput = document.getElementById("input-min");
const maxInput = document.getElementById("input-max");
const productsCatalogue = document.querySelector(".products-catalogue");
const productsGrid = document.querySelector(".products-grid");

// Mobile Sidebar
const openFilterBtn = document.getElementById("filter-btn-mobile");
const closeFilterBtn = document.getElementById("close-filter-btn-mobile");
const filterPanel = document.querySelector(".filter-panel");

let minPrice = params["maxPrice"] ?? null;
let maxPrice = params["maxPrice"] ?? null;
let category = params["category"] ?? null;
let rating = params["rating"] ?? null;

const getValue = (input) => parseInt(input.value) || 0;

// EVENT LISTENERS

// Mobile filter panel
openFilterBtn.addEventListener("click", () => {
    filterPanel.classList.add("active");
});

closeFilterBtn.addEventListener("click", () => {
    filterPanel.classList.remove("active");
});

// Category List
document.querySelectorAll(".category-list .option").forEach((option) => {
    option.addEventListener("click", function () {
        document.querySelectorAll(".category-list .option").forEach((opt) => {
            opt.classList.remove("active");
        });

        const selected = this.dataset.value;
        if (selected === category) {
            category = null;
        } else {
            category = selected;
            this.classList.add("active");
        }

        updateFilterBtnState();
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
        setTimeout(updateFilterBtnState, 0);
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
searchButton.addEventListener("click", applyFilter);
searchField.addEventListener("keydown", function (event) {
    if (event.key === "Enter") applyFilter();
});
searchField.addEventListener("input", () => {
    updateSearchButtonState(true);
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
            label = `Any rating`;
            break;
        case 5:
            label = `5 stars only`;
            break;
        default:
            label = `${number} stars and up`;
            break;
    }

    document.getElementById("rating-label").textContent = label;
    updateFilterBtnState();
}

function applyFilter() {
    minPrice = getValue(minInput);
    maxPrice = getValue(maxInput);

    // Update global params variable
    Object.assign(params, {
        minPrice,
        maxPrice,
        category,
        rating,
    });

    let queryString = getQueryString();
    refreshPartials(queryString);
    updateFilterBtnState(false);
    updateResultsLabel();
    searchButton.classList.add("disabled");
    history.replaceState(null, "", `/products${queryString}`);
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
        search: encodeURIComponent(searchField.value).trim(),
        category: params["category"] ?? null,
        minPrice: params["minPrice"] ?? null,
        maxPrice: params["maxPrice"] ?? null,
        rating: params["rating"] ?? null,
        page: params["page"] ?? null,
    };

    return buildQueryString(queryParams);
}

function refreshPartials(queryString) {
    // Fetch whole product display HTML
    fetch(`/partial/products-display${queryString}`)
        .then((res) => res.json())
        .then((data) => {
            // Replace product display HTML with new HTML
            if (data["products-display"] !== "") {
                productsCatalogue.classList.remove("empty");
                // Replace existing HTML
                productsGrid.innerHTML = data["products-display"];
                document.querySelector(".page-selector").innerHTML =
                    data["page-selector"];
            } else {
                // Replace page selector HTML with new HTML
                productsCatalogue.classList.add("empty");
                document.querySelector(".page-selector").innerHTML = "";
            }

            window.scrollTo(0, 0);
            resizeProductCardTitles();

            // Update page-selector
            setPageEventListeners();
        });
}

function updateResultsLabel() {
    let search = encodeURIComponent(searchField.value).trim();
    let header = document.getElementById("results-header");

    if (search) {
        header.innerHTML = `Results for <span>"${search}"</span>`;
        header.style.display = "block";
    } else {
        header.style.display = "none";
    }
}

function updateSearchButtonState() {
    if (searchField.value.trim() !== "") {
        searchButton.classList.remove("disabled");
    } else {
        searchButton.classList.add("disabled");
    }
}

function updateFilterBtnState(enable = true) {
    let minPrice = getValue(minInput);
    let maxPrice = getValue(maxInput);

    if ((maxPrice > 0 && minPrice > maxPrice) || !enable) {
        filterButton.classList.add("disabled");
        filterButton.removeEventListener("click", applyFilter);
    } else {
        filterButton.classList.remove("disabled");
        filterButton.addEventListener("click", applyFilter);
    }
}

// Initial setup
setPageEventListeners();
setRating(rating);
setPage();
updateResultsLabel();
updateFilterBtnState(false);

if (params["products-display"] === "") {
    productsCatalogue.classList.add("empty");
    document.querySelector(".page-selector").innerHTML = "";
}
