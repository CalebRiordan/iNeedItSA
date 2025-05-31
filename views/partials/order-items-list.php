<?php foreach ($items as $item): ?>

    <a href="<?= "/products/{$item->productId}" ?>" style="text-decoration: none; display: block;">
        <div id="order-item_<?= $item->id ?>" class="item order-item">
            <div class="left">
                <div class="image-container">
                    <img
                        src="<?= $item->displayImageUrl ? htmlspecialchars($item->displayImageUrl) : '/assets/images/product-placeholder.png' ?>"
                        alt="<?= htmlspecialchars($item->productName) ?>" />
                </div>

                <div class="item-info">
                    <span><?= $item->productName ?></span>
                    <p class="light-text">Qty: <?= $item->quantity ?></p>
                </div>
            </div>

            <h3 class="price">R<?= $item->price ?></h3>
        </div>
    </a>


<?php endforeach; ?>