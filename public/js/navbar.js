import { Cart } from "/js/utils/cart.js";

const navlinkCart = document.querySelector(".cart-nav-link");
const countBadge = document.querySelector(".nav-link .cart-count");

export function updateCartNavLinkCount() {
    if (navlinkCart) {
        if (Cart.uniqueCount > 0) {
            countBadge.hidden = false;
            countBadge.textContent = Cart.uniqueCount;
        } else {
            countBadge.hidden = true;
            countBadge.textContent = Cart.uniqueCount;
        }
    }
}

document.addEventListener("DOMContentLoaded", updateCartNavLinkCount);
