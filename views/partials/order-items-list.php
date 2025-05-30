<?php foreach ($items as $item): ?>

    <div id="order-item_<?= $item->id ?>" class="item order-item">
        <div class="left">
            <div class="image-container">
                <img
                    src="<?= $item->displayImageUrl ? htmlspecialchars($item->displayImageUrl) : '/assets/images/product-placeholder.png' ?>"
                    alt="<?= htmlspecialchars($item->name) ?>" />
            </div>

            <div class="item-info">
                <a href="/product/{<?= $item->productId ?>}" class="name"><?= $item->name ?></a>
                <p class="name"><?= $item->quantity ?></p>
            </div>
        </div>

        <h3 class="price">R<?= $item->price ?></h3>
    </div>


<?php endforeach; ?>