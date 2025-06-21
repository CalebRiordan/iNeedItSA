<?php

$stylesheets = ['seller/index.css'];
$scripts = ['seller/index.js', 'utils/scaleProductCardFont.js'];

require partial('header');
require partial('navbar');
?>

<main>
    <h1>Seller Dashboard</h1>

    <section class="seller-info-cards">
        <div class="info-card">
            <h2>Products Sold</h2>
            <p><?= $seller->productsSold; ?></p>
        </div>
        <div class="info-card">
            <h2>Total Views</h2>
            <p><?= $seller->views; ?></p>
        </div>
        <div class="info-card">
            <h2>Seller Verified</h2>
            <p><?= $seller->isVerified ? 'Yes' : 'No'; ?></p>
        </div>
        <div class="info-card">
            <h2>Date Registered</h2>
            <p><?= date('F j, Y', strtotime($seller->dateRegistered)); ?></p>
        </div>
    </section>

    <section class="seller-products-section">
        <h1 class="section-heading">Your Products</h1>
        <div class="product-row">
            <!-- Row Content -->
            <div class="product-row-inner">
                <?php foreach ($products as $product): ?>
                    <?php require partial('product-card-seller'); ?>
                <?php endforeach; ?>

                <!-- Create New Listing Card -->
                <a class="product-card create-new-card" href="/products/new">
                    <span class="plus-icon">+</span>
                    <p>Create New Listing</p>
                </a>
            </div>
        </div>
    </section>

</main>

<?php require_once partial('toast') ?>
<?php require_once partial('confirmation-modal') ?>
<?php require partial('footer') ?>