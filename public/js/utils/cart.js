export class Cart {
    static get items() {
        return JSON.parse(localStorage.getItem("cart")) || [];
    }

    static async add(id, quantity, price) {
        const cart = this.items;
        cart.push({ product_id: id, quantity: quantity, price: price });
        localStorage.setItem("cart", JSON.stringify(cart));
        await this.persist();
    }

    static async remove(id) {
        let cart = this.items;
        cart = cart.filter(item => item.product_id !== id);
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
        console.log("fetch");
        
        fetch("/cart", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ items: this.items }),
        })
            .then(async (res) => {
                const data = await res.json();
                console.log(data);                
            })
            .catch((error) => {
                window.location.href = "/500";
            });
    }
}
