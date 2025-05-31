export class ConfirmModal {
    constructor() {
        this.element = document.getElementById("confirm-modal");
        this.confirmBtn = document.getElementById("confirm-yes");
        this.cancelBtn = document.getElementById("confirm-no");
        this.messageElement = this.element.querySelector("p");
        this.onConfirmCallback = null;

        this.confirmBtn.addEventListener("click", () => {
            this.hide(true);
        });

        this.cancelBtn.addEventListener("click", () => this.hide);

        this.element.addEventListener("click", (e) => {
            if (e.target === this.element) {
                this.hide(false);
            }
        });
    }

    show(message, callback) {
        if (!this.element) {
            console.error("No confirmation modal found in the DOM");
        }

        this.onConfirmCallback = callback;
        this.messageElement.textContent = message;
        this.element.classList.remove("hidden");
        
        this.confirmBtn.focus();
    }

    hide(confirm = false) {
        if (!this.element) {
            console.error("No confirmation modal found in the DOM");
        }

        if (this.onConfirmCallback) this.onConfirmCallback(confirm);

        this.element.classList.add("hidden");
    }
}
