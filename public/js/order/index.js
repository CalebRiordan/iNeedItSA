import { Cart } from "/js/utils/cart.js";

const totalQty = document.getElementById("total-qty");
const totalPrice = document.getElementById("total-price");
let cartDisplay;
let emptyCartDisplay;

// Functions

// Cart HTML
async function fetchCartPartial() {
    const cart = JSON.parse(localStorage.getItem("cart") || "[]");

    if (cart.length > 0) {
        try {
            const res = await fetch("/partial/cart", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ items: cart }),
            });

            if (!res.ok) {
                const error = await res.text();
                return null;
            }

            const data = await res.json();
            return data;
        } catch (err) {
            return null;
        }
    } else {
        return "";
    }
}

// Order HTML
async function fetchOrder(id) {
    try {
        const res = await fetch(`partial/order/${id}`);
        const data = await res.json();
        return data;
    } catch (err) {
        return null;
    }
}

// Update checkout card price and quantity
function updateCheckoutCard() {
    const qty = Cart.totalQuantity;
    totalQty.textContent = qty === 1 ? `(${qty} item)` : `(${qty} items)`;
    totalPrice.textContent = `R${Cart.totalPrice}`;
}

// Add functionality to cart item buttons
function setItemActions() {
    const spinners = document.querySelectorAll(".qty-spinner");
    const deleteBtns = document.querySelectorAll(".remove-item");

    spinners.forEach((spinner) => {
        spinner.addEventListener("change", async () => {
            // When user changes quantity of item:
            const id = spinner.dataset.productId;
            const qty = parseInt(spinner.value, 10);
            await Cart.updateQuantity(id, qty); // Update on server
            updateCheckoutCard();
        });
    });

    deleteBtns.forEach((btn) => {
        btn.addEventListener("click", async () => {
            // When user deletes item:
            const id = btn.dataset.productId;
            await Cart.remove(id); // Update on server
            document.getElementById(`item_${id}`).remove();
            Cart.updateNavLinkCount();
            if (Cart.items.length === 0) {
                cartEmpty();
            }
        });
    });
}

// Show empty-cart HTML
function cartEmpty() {
    cartDisplay.hidden = true;
    emptyCartDisplay.hidden = false;
}

document.querySelectorAll(".order").forEach((order) => {
    // Show order items (history) when order is clicked
    order.addEventListener("click", async function () {
        const panel = this.querySelector(".order-items");
        const chevron = this.querySelector(".chevron");

        const isOpen = !panel.hasAttribute("hidden");

        // Close panel
        if (isOpen) {
            panel.hidden = true;
            chevron.classList.remove("down");
        } else {
            // Open panel - list of order items
            panel.hidden = false;
            chevron.classList.add("down");

            if (!panel.loaded) {
                panel.innerHTML = "<p>Loading order items...</p>";
                const orderItems = await fetchOrder(
                    parseInt(this.dataset.orderId) 
                ); // Fetch order items from server as HTML

                if (orderItems) {
                    panel.innerHTML = orderItems;
                    panel.loaded = true;
                } else {
                    panel.innerHTML =
                        "<h1 class='centre-content'>Error Loading Order History</h1>";
                }
            }
        }

        // Open smoothly
        requestAnimationFrame(() => {
            panel.scrollIntoView({ behavior: "smooth", block: "start" });
        });
    });
});

// Initial Setup - fetch cart when JS loads
const partial = await fetchCartPartial();

document.querySelector(".loading").hidden = true;
emptyCartDisplay = document.querySelector(".cart-empty");
cartDisplay = document.querySelector(".cart-wrapper");

if (partial === "") {
    emptyCartDisplay.hidden = false;
} else if (partial === null) {
    cartDisplay.hidden = false;
    cartDisplay.innerHTML =
        "<h1 class='centre-content'>Error Loading Cart</h1>";
} else {
    cartDisplay.hidden = false;
    document.querySelector(".cart").innerHTML = partial;
    updateCheckoutCard();
    setItemActions();
}