import { Cart } from "/js/utils/cart.js";

const addToCartBtns = document.querySelectorAll("a.add-cart-btn");
const loading = document.querySelector(".loading");
const productId = document.getElementById("product-id");
const price = document.getElementById("product-price");
const shareButton = document.getElementById("share-btn");
const copiedPopup = document.getElementById("copied-popup");

updateProduct(Cart.itemExists(productId.value) ? "add" : "");

// Event Listeners
addToCartBtns.forEach((btn) => {
    btn.addEventListener("click", async (e) => {
        e.preventDefault();

        if (btn.dataset.restricted) {
            window.location.href = "/login?previous=/products/show";
        } else if (!Cart.itemExists(productId.value)) {
            updateProduct("pending");
            await Cart.add(productId.value, 1, price.value);
            updateProduct("add");
            Cart.updateNavLinkCount();
        }
    });
});

shareButton.addEventListener("click", async () => {
    await navigator.clipboard.writeText(window.location.href);
    copiedPopup.classList.add("show");
    setTimeout(() => {
        copiedPopup.classList.remove("show");
    }, 2000); // Popup visible for 2 seconds (2000 milliseconds)
});

// Functions
function updateProduct(state) {
    const notAdded = document.querySelectorAll(".item-not-added");
    const added = document.querySelectorAll(".item-added");
    const isAdded = state === "add";
    const isPending = state === "pending";

    loading.hidden = !isPending;
    addToCartBtns.forEach((btn) => (btn.disabled = isPending));

    added.forEach((el) => (el.hidden = !isAdded));
    notAdded.forEach((el) => (el.hidden = isAdded || isPending));

    addToCartBtns.forEach((btn) => {
        btn.classList.toggle("product-added", isAdded);
    });
}
