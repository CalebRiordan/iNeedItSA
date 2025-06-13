<?php

$stylesheets = ['seller/index.css'];

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
                <?php endforeach; ?>

                <!-- Create New Listing Card -->
                <a href="/products/new" class="product-card create-new-card">
                    <span class="plus-icon">+</span>
                    <p>Create New Listing</p>
                </a>
            </div>
        </div>
    </section>


</main>

<?php require partial('footer') ?>