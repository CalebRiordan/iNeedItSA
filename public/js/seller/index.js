import { ConfirmModal } from "/js/utils/modal.js";
import { showToast } from "/js/utils/toast.js";

const productCards = document.querySelectorAll(".product-card");
const modal = new ConfirmModal();

// Event listeners for each product card
productCards.forEach((card) => {
    // Skip 'Create New Listing' card
    if (card.classList.contains("create-new-card")) {
        return;
    }

    // Redirect to appropriate product information page
    const id = card.dataset.id;
    card.addEventListener("click", function () {
        console.log("redirect");

        window.location.href = "/products/" + id;
    });

    const $deleteBtn = card.querySelector(".delete-button");
    if (!$deleteBtn) {
        return;
    }

    $deleteBtn.addEventListener("click", async (e) => {
        console.log("delete");

        e.stopPropagation();
        e.preventDefault();
        const loading = card.querySelector(".overlay");

        // Show model to confirm deletion
        modal.setButtons("confirm", "cancel");
        modal.show(
            "Are you sure you want to remove this product from your listings?",
            async (confirmed) => {
                loading.hidden = false;
                if (confirmed) {
                    try {
                        const res = await fetch(`/products/${id}`, {
                            method: "DELETE",
                        });

                        if (!res.ok) {
                            const error = await res.text();
                            console.error(error);
                            showToast(
                                "Something went wrong. Product could not be removed. Please try again later",
                                "error"
                            );
                            loading.hidden = true;
                            return;
                        }

                        // Remove product card
                        const data = await res.text();
                        console.log(data);
                        card.remove();
                        showToast("Product listing removed", "success");
                    } catch (err) {
                        console.error(err);

                        showToast(
                            "Something went wrong. Product could not be removed. Please try again later",
                            "error"
                        );
                        loading.hidden = true;
                    }
                }

                loading.hidden = true;
            }
        );
    });
});
