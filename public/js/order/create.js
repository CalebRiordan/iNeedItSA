import { ConfirmModal } from "/js/utils/modal.js";

const addressInput = document.getElementById("address");
const cardInput = document.getElementById("card-num");
const csvInput = document.getElementById("csv-code");
const cardInputError = document.querySelector(".error-card-num");
const csvInputError = document.querySelector(".error-csv");
const form = document.getElementById("checkout-form");


let address = addressInput.value;
const modal = new ConfirmModal();

// ========== Address Input Validation ==========
addressInput.addEventListener("change", function () {
    const newAddress = addressInput.value;
    if (newAddress !== address) {
        if (newAddress.length <= 5) {
            addressInput.value = address;
        } else {
            modal.setButtons("yes", "no");
            modal.show(
                "Are you sure you want to change the shipping address for this order?",
                (confirmed) => {
                    if (confirmed) {
                        address = newAddress;
                    } else {
                        addressInput.value = address;
                    }
                }
            );
        }
    }
});

addressInput.addEventListener("keydown", function (e) {
    if (e.key === "Enter") {
        addressInput.blur();
    }
});

// ========== Card Number Input Validation ==========
cardInput.addEventListener("input", function () {
    this.value = this.value.replace(/\D/g, "");

    if (this.value.length > 16) {
        this.value = this.value.slice(0, 16);
        cardInputError.textContent = "";
    }
});

cardInput.addEventListener("blur", function () {
    if (this.value.length < 16 && this.value.length > 0) {
        cardInputError.textContent = "Card number must be 16 digits";
    } else {
        cardInputError.textContent = "";
    }
});

// ========== CSV Code Input Validation ==========
csvInput.addEventListener("input", function () {
    this.value = this.value.replace(/\D/g, "");

    if (this.value.length > 3) {
        this.value = this.value.slice(0, 3);
        csvInputError.textContent = "";
    }
});

csvInput.addEventListener("blur", function () {
    if (this.value.length < 3 && this.value.length > 0) {
        csvInputError.textContent = "CSV must be exactly 3 digits";
    } else {
        csvInputError.textContent = "";
    }
});

// ========== Final validation & Form submission ==========
form.addEventListener("submit", (e) => {
    e.preventDefault();

    // Validate Card Number (16 digits only)
    if (cardInput.value.length !== 16) {
        cardInputError.textContent = "Card number must be 16 digits";
        return;
    }

    // Validate CSV code (3 digits only)
    const csvInput = document.getElementById("csv-code");
    if (!/^\d{3}$/.test(csvInput.value)) {
        csvInputError.textContent = "CSV must be exactly 3 digits";
        return;
    }

    // Confirm modal
    modal.setButtons("confirm", "cancel");
    modal.show("Are you sure you want to place order?", (confirmed) => {
        if (confirmed) {
            form.submit();
        }
    });
});
