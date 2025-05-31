import { ConfirmModal } from "/js/utils/modal.js";

const addressInput = document.getElementById("address");
let address = addressInput.value;
const modal = new ConfirmModal();

addressInput.addEventListener("change", function (e) {
    if (addressInput.value !== address) {
        modal.show(
            "Are you sure you want to change the shipping address for this order?",
            (confirmed) => {
                if (confirmed) {
                    address = addressInput.value;
                } else {
                    addressInput.value = address;
                }
            }
        );
    }
});

addressInput.addEventListener("keydown", function (e) {
    if (e.key === "Enter") {
        addressInput.blur();
    }
});