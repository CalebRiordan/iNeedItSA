import { ConfirmModal } from "/js/utils/modal.js";

const addressInput = document.getElementById("address");
const cardInput = document.getElementById("card-num");
const submitBtn = document.querySelectory(".checkout-container form button");
cardInputError = document.querySelector(".error-card-num");

let address = addressInput.value;
const modal = new ConfirmModal();

addressInput.addEventListener("change", function (e) {
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

submitBtn.addEventListener("click", (e) => {
    if (cardInput.value.length !== 16) {
        e.preventDefault();
        cardInputError.textContent = "Card number must be 16 digits";
    } else {
        modal.setButtons("confirm", "cancel");
        modal.show("Are you sure you want to place order?", (confirm) => {
            if (!confirm) {
                e.preventDefault();
            }
        });
    }
});
