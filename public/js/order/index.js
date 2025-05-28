// Functions
function fetchCartPartial() {
  const cart = JSON.parse(localStorage.getItem("cart") || "[]");
  if (cart.length > 0) {
    fetch("/partial/cart", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ items: cart }),
    })
      .then((res) => res.json())
      .then((data) => {
        document.querySelector(".cart-section").innerHTML = data;
      })
      .catch((err) => {
        document.querySelector(".cart-section").innerHTML =
          "<h1>Error Loading Cart</h1>";
      });
  }
}

// Event Listeners
document.addEventListener("DOMContentLoaded", fetchCartPartial);
