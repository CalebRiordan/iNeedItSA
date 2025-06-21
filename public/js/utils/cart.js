export class Cart {
    static get items() {
        const items = JSON.parse(localStorage.getItem("cart"));
        // Make sure it's an array
        return Array.isArray(items) ? items : [];
    }

    static set(items) {
        localStorage.setItem("cart", JSON.stringify(items));
        this.updateNavLinkCount();
    }

    static async add(id, quantity, price) {
        const cart = this.items;
        cart.push({ product_id: id, quantity: quantity, price: price });
        localStorage.setItem("cart", JSON.stringify(cart));
        await this.persist();
    }

    static async remove(id) {
        let cart = this.items;
        id = Number(id);
        cart = cart.filter((item) => item.product_id !== id);
        localStorage.setItem("cart", JSON.stringify(cart));
        await this.persist();
    }

    static itemExists(id) {
        return !!this.items.find((item) => item.product_id === id);
    }

    static get uniqueCount() {
        return this.items.length;
    }

    static get totalQuantity() {
        return this.items.reduce((total, item) => total + item.quantity, 0);
    }

    static get totalPrice() {
        return this.items.reduce(
            (total, item) => total + item.quantity * item.price,
            0
        );
    }

    static async updateQuantity(id, qty) {
        const cart = this.items;
        cart.forEach((item) => {
            if (item.product_id === id) {
                item.quantity = qty;
            }
        });
        localStorage.setItem("cart", JSON.stringify(cart));
        await this.persist();
    }

    static async persist() {
        fetch("/cart", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ items: this.items }),
        })
            .then(async (res) => {
                const data = await res.json();
            })
            .catch((error) => {
                window.location.href = "/status/500";
            });
    }

    static updateNavLinkCount() {
        const countBadge = document.querySelector(".nav-link .cart-count");

        if (document.querySelector(".cart-nav-link")) {
            if (Cart.uniqueCount > 0) {
                countBadge.hidden = false;
                countBadge.textContent = Cart.uniqueCount;
            } else {
                countBadge.hidden = true;
                countBadge.textContent = Cart.uniqueCount;
            }
        }
    }
}
