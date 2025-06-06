import { Cart } from "/js/utils/cart.js";

// Sync cart from server
document.addEventListener("DOMContentLoaded", () => {
    const cart = window.cartData ?? null;
    if (cart) {
        Cart.set(cart);
    }

    Cart.updateNavLinkCount();
});

// Mobile Sidebar
const hamburger = document.getElementById("hamburger");
const navMenu = document.querySelector(".navbar .right");

hamburger.addEventListener("click", () => {
    navMenu.classList.toggle("active");
});
