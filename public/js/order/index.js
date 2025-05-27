localStorage.setItem(
  "cart",
  JSON.stringify([
    { product_id: 5, quantity: 2 },
    { product_id: 22, quantity: 1 },
    { product_id: 48, quantity: 1 },
  ])
);

// Functions
function fetchCart() {
  const cart = JSON.parse(localStorage.getItem("cart") || "[]");
  if (cart.length > 0) {
    fetch("/partial/cart", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ items: cart }),
    })
      .then((res) => res.json())
      .then((data) => {
        console.log("Cart product details:", data);
        document.querySelector(".cart-section").innerHTML = data;
      })
      .catch((err) => {
        console.log(err);
        document.querySelector(".cart-section").innerHTML =
          "<h1>Error Loading Cart</h1>";
      });
  }
}

// Event Listeners
document.addEventListener("DOMContentLoaded", fetchCart);
