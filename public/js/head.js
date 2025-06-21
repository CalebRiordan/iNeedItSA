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

if (hamburger) {
    hamburger.addEventListener("click", () => {
        navMenu.classList.toggle("active");
    });
}

// Close the side bar if cart or order navlink is clicked when already on Orders page
document.querySelectorAll(".orders-link").forEach((link) => {
    link.addEventListener("click", () => {        
        if (window.location.pathname === "/order") {
            // Close desktop sidebar
            const sidebar = document.querySelector(".sidebar");
            const overlay = document.querySelector(".sidebar-overlay");
            sidebar.classList.remove("show");
            overlay.classList.remove("show");

            // Close mobile sidebar
            if (hamburger) {
                navMenu.classList.remove("active");
            }
        }
    });
});
