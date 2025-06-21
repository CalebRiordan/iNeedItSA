import { Cart } from "/js/utils/cart.js";
import { sanitise } from "/js/utils/sanitisation.js";
import { showToast } from "/js/utils/toast.js";

const addToCartBtns = document.querySelectorAll("a.add-cart-btn");
const loading = document.querySelector(".loading");
const productId = document.getElementById("product-id");
const price = document.getElementById("product-price");
const shareButton = document.getElementById("share-btn");
const copiedPopup = document.getElementById("copied-popup");

// Reviews
const stars = document.querySelectorAll(".create-review .star");
const submitReviewbtn = document.querySelector(".create-review button");
const deleteReviewBtn = document.getElementById("delete-review");

updateProduct(Cart.itemExists(productId.value) ? "add" : "");

// Functions
function updateProduct(state) {
    // Updates the Add To Cart button(s) depending on whether the user has added the product to their cart of not
    const notAdded = document.querySelectorAll(".item-not-added");
    const added = document.querySelectorAll(".item-added");
    const isAdded = state === "add";
    const isPending = state === "pending";

    loading.hidden = !isPending;
    addToCartBtns.forEach((btn) => (btn.disabled = isPending));

    added.forEach((el) => (el.hidden = !isAdded));
    notAdded.forEach((el) => (el.hidden = isAdded || isPending));

    addToCartBtns.forEach((btn) => {
        btn.classList.toggle("product-added", isAdded);
    });
}

// Event Listeners
addToCartBtns.forEach((btn) => {
    btn.addEventListener("click", async (e) => {
        e.preventDefault();

        // Prevent interaction if not logged in
        if (btn.dataset.restricted) {
            window.location.href = "/login?previous=/products/show";
        } else if (!Cart.itemExists(productId.value)) {
            // Update Add To Cart buttons and add product to static Cart object
            updateProduct("pending");
            await Cart.add(productId.value, 1, price.value);
            updateProduct("add");
            Cart.updateNavLinkCount();
        }
    });
});

shareButton.addEventListener("click", async () => {
    await navigator.clipboard.writeText(window.location.href);
    copiedPopup.classList.add("show");
    setTimeout(() => {
        copiedPopup.classList.remove("show");
    }, 2000); // Popup visible for 2 seconds (2000 milliseconds)
});

if (submitReviewbtn) {
    // Star/rating selector for review creation
    stars.forEach((star) =>
        star.addEventListener("click", () => {
            rating = star.dataset.value;

            stars.forEach((star, i) => {
                star.textContent = i < rating ? "★" : "☆";
            });

            document.getElementById("rating").value = rating;
            submitReviewbtn.classList.remove("disabled");
        })
    );

    // On review submit
    submitReviewbtn.addEventListener("click", (e) => {
        const rating = document.getElementById("rating");

        // rating is required
        if (rating <= 0) {
            e.preventDefault();
            document.querySelector(".error-rating").textContent =
                "Please rate the product out of 5";
        }

        let comment = document.getElementById("comment");
        comment.value = sanitise(comment.value);
    });
}

if (deleteReviewBtn) {
    deleteReviewBtn.addEventListener("click", async function () {
        const url = `/review/${this.dataset.value}`;

        const res = await fetch(url, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
            },
        });

        if (res.ok) {
            window.location.hash = 'reviews';
            window.location.reload();
            showToast("Review deleted");
        } else {
            showToast(
                "An error occurred while trying to delete your review. Sorry for the inconvenience. We're working on fixing this!",
                "error"
            );
        }
    });
}
