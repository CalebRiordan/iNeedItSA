import { Cart } from "/js/utils/cart.js";

const navlinkCart = document.querySelector(".nav-link.cart");
const countBadge = document.querySelector(".nav-link .cart-count");

export function updateCartNavLinkCount(){
    if (Cart.uniqueCount > 0){
        countBadge.hidden = false;
        countBadge.textContent = Cart.uniqueCount;
    } else {
        countBadge.hidden = true;
        countBadge.textContent = Cart.uniqueCount;
    }
}

document.addEventListener("DOMContentLoaded", updateCartNavLinkCount);