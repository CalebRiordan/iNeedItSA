<?php

$stylesheets = ['seller/index.css'];
$scripts = ['seller/index.js', 'utils/scaleProductCardFont.js'];

require partial('header');
require partial('navbar');
?>

<main>
    <h1>Seller Dashboard</h1>

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