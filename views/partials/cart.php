<?php foreach ($products as $i => $product): ?>

    <div id="item_<?= $product->id ?>" class="item cart-item" data-id=<?= $product->id ?>>
        <div class="image-container">
            <img
                src="<?= $product->displayImageUrl ? htmlspecialchars($product->displayImageUrl) : '/assets/images/product-placeholder.png' ?>"
                alt="<?= htmlspecialchars($product->name) ?>" />
        </div>

        <div class="product-info">
            <p class="name"><?= $product->name ?></p>
            <p class="price">
                R<?= $product->discount > 0
                        ? number_format($product->price * ((100 - $product->discount) / 100), 0)
                        : number_format($product->price) ?>
            </p>
            <?php if ($product->discount > 0): ?>
                <span class="discount-badge"><?= $product->discount ?>% promotion applied!</span>
            <?php endif; ?>
        </div>

        <div class="item-actions">
            <div class="remove-item" data-product-id="<?= $product->id ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z" />
                </svg>
            </div>

            <div class="quantity-selector">
                <?php $qtyId = "qty_{$i}" ?>
                <label for="<?= $qtyId ?>">Qty:</label>
                <br>
                <input class="qty-spinner" id="<?= $qtyId ?>" data-product-id="<?= $product->id ?>" type="number" min="1" max="50" value=<?= $product->quantity ?? 1 ?>>
            </div>

        </div>
    </div>

<?php endforeach; ?>

<script>
    // Redirect to item's corresponding product on click. 
    // Cannot wrap item in <a> or make the cart-item container an <a> without affecting
    // functionality of internal buttons
    const items = document.querySelectorAll(".item .cart-item");
    items.forEach((item) => {
        item.addEventListener("click", (e) => {
            window.location.hred = `/product/${item.dataset.id}`;
        })
    })
</script>