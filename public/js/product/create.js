import { sanitise, anySpecialChars } from "/js/utils/sanitisation.js";

// Form inputs
const name = document.getElementById("name");
const description = document.getElementById("description");
const price = document.getElementById("price");
const stock = document.getElementById("stock");
const discount = document.getElementById("discount");
const form = document.querySelector(".form-container form");

// Image
const preview = document.getElementById("product-img-preview");
const imageInput = document.getElementById("product-img");
const image = document.getElementById("img-container");
const removeImageBtn = document.querySelector(".image-input .remove-btn");
const imageChangedEl = document.getElementById("image-changed");

// Submit button
const submit = document.querySelector(".form-container #btn-submit");
let formValid = true;

function previewProductImg(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            selectImage(e.target.result);
        };
        reader.readAsDataURL(input.files[0]);

        document.querySelector(".error-image").textContent = "";
    }
}
window.previewProductImg = previewProductImg;

function selectImage(src) {
    preview.src = src;
    preview.classList.remove("placeholder");
    removeImageBtn.style.display = "block";
    if (imageChangedEl) {
        imageChangedEl.value = true;
    }
}

function removeImage() {
    imageInput.value = "";
    preview.src = "";
    removeImageBtn.style.display = "none";
    preview.classList.add("placeholder");
    if (imageChangedEl) {
        imageChangedEl.value = true;
    }
}

function clearErrorOnInput(field, errorElement) {
    // Define the handler so it can be removed later
    function clearErrorHandler() {
        errorElement.textContent = "";
        field.removeEventListener("input", clearErrorHandler);
    }

    field.addEventListener("input", clearErrorHandler);
}

function showError(field, message, errorElement = null) {
    formValid = false;

    if (!errorElement){
        errorElement = field.closest(".input-group").querySelector(".error");
    }
    errorElement.textContent = message;

    clearErrorOnInput(field, errorElement);
}

function validateNumber(element, maxLength) {
    const el = element;
    el.value = el.value.replace(/\D/g, "");

    if (el.value.length > maxLength) {
        el.value = el.value.slice(0, maxLength);
    }

    if (el.value.length === 2 && el.value.startsWith("0")) {
        // If the first char is "0", replace the user input
        el.value = el.value[1];
        el.setSelectionRange(1, 1);
    }
}

// Event Listeners

image.addEventListener("click", () => imageInput.click());
imageInput.addEventListener("change", previewProductImg);

// Validate name
name.addEventListener("blur", function () {
    if (this.value.length < 30) {
        const errorEl = document.querySelector(".error-name");
        errorEl.textContent =
            "Please use at least 30 characters for a good name";
        clearErrorOnInput(this, errorEl);
    }
});

// Validate description
description.addEventListener("blur", function () {
    if (this.value.length < 30) {
        const errorEl = document.querySelector(".error-description");
        errorEl.textContent =
            "Please use at least 50 characters - describe more about the product";
        clearErrorOnInput(this, errorEl);
    }
});

// Validate Price
price.addEventListener("input", function () {
    document.querySelector(".error-price").textContent = "";
    validateNumber(this, 5);
});

price.addEventListener("blur", function () {
    if (parseInt(this.value) < 20) {
        document.querySelector(".error-price").textContent =
            "Price must be at least R20";
    }
});

// Validate Stock
stock.addEventListener("input", function () {
    document.querySelector(".error-stock").textContent = "";

    validateNumber(this, 3);
});

// Validate Discount
discount.addEventListener("input", function () {
    document.querySelector(".error-discount").textContent = "";

    validateNumber(this, 2);
});

form.addEventListener("submit", (e) => {
    formValid = true;
    e.preventDefault();

    // Validate product image
    const errorEl = document.querySelector('.error-image')
    if (imageChangedEl && imageChangedEl.value === true) {
        showError(
            imageInput,
            "Please choose a product thumbnail image",
            errorEl
        );
    } else if (imageInput.value === "") {
        showError(imageInput, "Please supply a product image", errorEl);
    }

    // Validate remaining required fields
    document.querySelectorAll("[required]").forEach((field) => {
        if (!formValid) return;

        const val = field.value.trim();
        if (val === "") {
            showError(field, "Please fill out this field.");
        } else if (field.id === "price" && parseInt(val) < 20) {
            // Price error
            showError(field, "Price must be at least R20");
        } else if (
            !(
                field.id === "name" ||
                field.id === "description" ||
                field.id === "category" ||
                field.id === "product-img"
            ) &&
            anySpecialChars(val, "'&^%|.,")
        ) {
            // Reject special chars in all other fields
            showError(field, "Please avoid using special characters.");
        } else {
            // sanitize input
            field.value = sanitise(field.value);
            form.submit();
        }
    });
});

removeImageBtn.addEventListener("click", removeImage);
