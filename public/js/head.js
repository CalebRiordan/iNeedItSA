import { Cart } from "/js/utils/cart.js";

document.addEventListener('DOMContentLoaded', () => {
    const cart = window.cartData ?? null
    if (cart){
        console.log(cart);
        Cart.set(cart);
    }

    console.log("updated cart icon");    
    Cart.updateNavLinkCount();
});