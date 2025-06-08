<?php

$stylesheets = ['category-bar.css', 'products-search-bar.css', 'product-row.css', 'index.css'];
$scripts = ['products-search-bar.js', 'product-row.js', 'utils/scaleProductCardFont.js'];

require partial('header');
require partial('navbar');
require partial('category-bar');
?>

<main>
    <!-- Search Bar -->
    <div class='centre-content search-bar-area'>
        <?php require partial('products-search-bar'); ?>
    </div>

    <!-- Featured Products Row -->
    <div class='centre-content featured-products-area'>
        <?php
        $products = $featuredProducts;
        $sectionTitle = 'Featured Products';
        require partial('product-row'); ?>
    </div>

    <!-- Discounted Products Row -->
    <div class='centre-content featured-products-area'>
        <?php
        $products = $discountedProducts;
        $sectionTitle = 'On Promotion';
        require partial('product-row'); ?>
    </div>

    <!-- Top Rated Products Row -->
    <div class='centre-content featured-products-area'>
        <?php
        $products = $topRatedProducts;
        $sectionTitle = 'Top rated';
        require partial('product-row'); ?>
    </div>

</main>

<?php require_once partial('toast') ?>
<?php require partial('footer'); ?>