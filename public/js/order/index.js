import { Cart } from "/js/utils/cart.js";
import { updateCartNavLinkCount } from "/js/navbar.js";

const totalQty = document.getElementById("total-qty");
const totalPrice = document.getElementById("total-price");
let cartDisplay;
let emptyCartDisplay;


// Functions
async function fetchCartPartial() {
    const cart = JSON.parse(localStorage.getItem("cart") || "[]");

    if (cart.length > 0) {
        try {
            const res = await fetch("/partial/cart", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ items: cart }),
            });

            const data = await res.json();
            return data;
        } catch (err) {
            return null;
        }
    } else {
        return "";
    }
}

function updateCheckoutCard() {
    const qty = Cart.totalQuantity;
    totalQty.textContent = qty === 1 ? `(${qty} item)` : `(${qty} items)`;
    totalPrice.textContent = `R${Cart.totalPrice}`;
}

function setItemActions() {
    const spinners = document.querySelectorAll(".qty-spinner");
    const deleteBtns = document.querySelectorAll(".remove-item");

    spinners.forEach((spinner) => {
        spinner.addEventListener("change", async () => {
            const id = spinner.dataset.productId;
            const qty = parseInt(spinner.value, 10);
            await Cart.updateQuantity(id, qty);
            updateCheckoutCard();
        });
    });

    deleteBtns.forEach((btn) => {
        btn.addEventListener("click", async () => {
            const id = btn.dataset.productId;
            await Cart.remove(id);
            document.getElementById(`item_${id}`).remove();
            updateCartNavLinkCount()
            if ((Cart.items.length === 0)) {
                cartEmpty();
            }
        });
    });
}

function cartEmpty() {
  cartDisplay.hidden = true;
  emptyCartDisplay.hidden = false;
}

// Event Listeners
document.addEventListener("DOMContentLoaded", async () => {
    const partial = await fetchCartPartial();

    document.querySelector(".loading").hidden = true;
    emptyCartDisplay = document.querySelector(".cart-empty")
    cartDisplay = document.querySelector(".cart-wrapper")

    if (partial === "") {
      emptyCartDisplay.hidden = false;
    } else if (partial === null) {
        document.querySelector(".cart").innerHTML =
            "<h1 class='centre-content'>Error Loading Cart</h1>";
    } else {
        cartDisplay.hidden = false;
        document.querySelector(".cart").innerHTML = partial;
        updateCheckoutCard();
        setItemActions();
    }
});
