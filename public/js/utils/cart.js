export class Cart {
    static get() {
        return JSON.parse(localStorage.getItem("cart")) || [];
    }

    static async add(id, quantity) {
        const cart = this.get();
        cart.push({ product_id: id, quantity: quantity });
        localStorage.setItem("cart", JSON.stringify(cart));
        await this.persist();
    }

    static itemExists(id) {
        return !!this.get().find((item) => item.product_id === id);
    }

    static volume(){
        return this.get().length;
    }

    static async persist() {
        fetch("/cart", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ items: this.get() }),
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
