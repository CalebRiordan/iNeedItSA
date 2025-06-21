export function showToast(message, type = "info", msDuration = 5000) {
    const toast = document.getElementById("toast");
    const toastText = document.getElementById("toast-text");

    if (!toast) {
        console.error('Toast element (id="toast") not found in the DOM.');
        return;
    }

    toastText.innerHTML = message;
    toast.className = type;

    // Show
    setTimeout(() => {
        toast.classList.add("show");
    }, 10);

    // Hide
    setTimeout(() => {
        toast.classList.remove("show");
        const handleTransitionEnd = () => {
            toast.style.display = "none";
            toast.removeEventListener("transitionend", handleTransitionEnd);
        };
        toast.addEventListener("transitionend", handleTransitionEnd, {
            once: true,
        }); // Listen only once
    }, msDuration);
}

// Check for message on page load
const toastDataInput = document.getElementById("toast-data");

if (toastDataInput && toastDataInput.value) {
    const message = toastDataInput.value;
    const type = toastDataInput.dataset.type || "info";
    const duration = toastDataInput.dataset.duration || 5000;

    showToast(message, type, duration);

    // IMPORTANT: Clear the hidden input's value and data-type after showing the toast.
    toastDataInput.value = "";
    toastDataInput.dataset.type = "";
    toastDataInput.dataset.duration = null;
}
