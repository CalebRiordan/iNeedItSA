import { Cart } from "/js/utils/cart.js";

document.addEventListener('DOMContentLoaded', () => {
    const cart = window.cartData ?? null
    if (cart){
        Cart.set(cart);
    }

    Cart.updateNavLinkCount();
});