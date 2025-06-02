import { Cart } from "/js/utils/cart.js";

const addToCartBtn = document.querySelector("a.add-cart-btn");
const loading = document.querySelector(".loading");
const productId = document.getElementById("product-id");
const price = document.getElementById("product-price");

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
        await Cart.add(productId.value, 1, price.value);
        updateProduct("add");
        Cart.updateNavLinkCount();
    }
});

// Functions
function updateProduct(state) {
    const notAdded = document.getElementById("item-not-added");
    const added = document.getElementById("item-added");

    loading.hidden = true;
    addToCartBtn.disabled = false;    

    if (state == "add") {
        added.hidden = false;
        notAdded.hidden = true;
        addToCartBtn.classList.add("product-added");
    } else if (state == "pending") {
        addToCartBtn.disabled = true;
        added.hidden = true;
        notAdded.hidden = true;
        loading.hidden = false;
    } else {
        added.hidden = true;
        notAdded.hidden = false;
        addToCartBtn.classList.remove("product-added");
    }
}
