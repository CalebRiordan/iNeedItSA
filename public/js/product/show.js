import { Cart } from "/js/utils/cart.js";

const addToCartBtn = document.querySelector("a.add-cart-btn");
const spinner = document.querySelector(".spinner");
const productId = document.getElementById("product-id");

// Event Listeners
document.addEventListener("DOMContentLoaded", () => {
    updateProduct(Cart.itemExists(productId.value) ? "add" : "");
});

addToCartBtn.addEventListener("click", async (e) => {
    e.preventDefault();

    if (addToCartBtn.dataset.restricted) {
        window.location.href = "/login?previous=/products/show";
    } else if (!Cart.itemExists(productId.value)) {
        updateProduct("pending");
        await Cart.add(productId.value, 1);
        updateProduct("add");

        // Update cart icon
    }
});

// Functions
function updateProduct(state) {
    const notAdded = document.getElementById("item-not-added");
    const added = document.getElementById("item-added");

    spinner.hidden = true;
    addToCartBtn.disabled = false;    

    if (state == "add") {
        added.hidden = false;
        notAdded.hidden = true;
        addToCartBtn.classList.add("product-added");
    } else if (state == "pending") {
        addToCartBtn.disabled = true;
        added.hidden = true;
        notAdded.hidden = true;
        spinner.hidden = false;
    } else {
        added.hidden = true;
        notAdded.hidden = false;
        addToCartBtn.classList.remove("product-added");
    }
}
