import { Cart } from "/js/utils/cart.js";

const navlinkCart = document.querySelector(".nav-link.cart");
const volumeBadge = document.querySelector(".nav-link .cart-volume");

function updateCartVolume(){
    if (Cart.volume() > 0){
        volumeBadge.hidden = false;
        volumeBadge.textContent = Cart.volume();
    } else {
        volumeBadge.hidden = true;
        volumeBadge.textContent = Cart.volume();
    }
}

document.addEventListener("DOMContentLoaded", updateCartVolume);