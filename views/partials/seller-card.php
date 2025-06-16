<?php

use Core\Session; ?>

<a class="add-cart-btn salient" data-restricted="<?= !Session::has('user') ? true : false ?>">
    <div class="content item-not-added">
        <span>Add to Cart</span>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
            <path d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96zM252 160c0 11 9 20 20 20l44 0 0 44c0 11 9 20 20 20s20-9 20-20l0-44 44 0c11 0 20-9 20-20s-9-20-20-20l-44 0 0-44c0-11-9-20-20-20s-20 9-20 20l0 44-44 0c-11 0-20 9-20 20z" />
        </svg>
    </div>

    <div class="content item-added" hidden>
        <span>Added to Cart!</span>
    </div>

    <div class="content">
        <span class="loading" hidden></span>
    </div>
</a>

<?php $emp = Session::get('emp'); ?>
<?php $role = $emp['role'] ?? null; ?>
<?php if ($emp && ($role === 'admin' || $role === 'pm')): ?>
    <!-- Edit Product button for staff with appropriate permissions -->
    <a class="edit-product-btn" href="/products/edit/<?= $product->id ?>">
        <div class="content item-not-added">
            <span>Edit Product (staff)</span>
        </div>
    </a>
<?php endif; ?>

<div class="seller-card">
    <div class="header">
        <h1><?= $product->seller->firstName . " " . $product->seller->lastName ?></h1>
        <?php if ($product->seller->sellerProfile->isVerified): ?>
            <div class="verified">
                <span class="tag">Verified</span><br>
                <span>since <?= $product->seller->sellerProfile->dateVerified ?? "" ?></span>
            </div>
        <?php else: ?>
            <div class="verified">
                <span>Not verified</span>
            </div>
        <?php endif; ?>
    </div>


    <div class="details">
        <p>Seller Since <strong><?= date('Y', strtotime($product->seller->sellerProfile->dateRegistered)) ?></strong></p>

        <div class="aligned-grid">

            <span class="stat-value"><?= $product->seller->sellerProfile->productsSold ?></span>
            <span class="stat-name">Products Sold</span>
            <span class="stat-value">
                <?php
                $views = $product->seller->sellerProfile->views;
                echo $views < 1000 ? $views : number_format($views / 1000, 1) . 'k' ?>
            </span>
            <span class="stat-name">Views</span>

        </div>

        <div class="location">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                <path d="M192 0C86 0 0 86 0 192c0 77.4 106.1 215.6 167.4 284.5 9.6 10.9 26.7 10.9 36.3 0C277.9 407.6 384 269.4 384 192 384 86 298 0 192 0zm0 272c-44.2 0-80-35.8-80-80s35.8-80 80-80 80 35.8 80 80-35.8 80-80 80z" />
            </svg>
            <?= $product->seller->location ?>, <?= $product->seller->province ?>
        </div>

        <div class="seller-products-link">
            <a href="#">View Seller’s Products</a>
        </div>
    </div>
</div>