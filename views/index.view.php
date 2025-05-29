<?php

$stylesheets = ['category-bar.css', 'products-search-bar.css', 'product-row.css', 'index.css'];
$scripts = ['products-search-bar.js', 'product-row.js', 'utils/scaleProductCardFont.js'];

require base_path("views/partials/header.php");
require base_path('views/partials/navbar.php');

require base_path("views/partials/category-bar.php");
?>

<main>
    <!-- Search Bar -->
    <div class="centre-content search-bar-area">
        <?php require base_path("views/partials/products-search-bar.php"); ?>
    </div>

    <!-- Featured Products Row -->
    <div class="centre-content featured-products-area">
        <?php
        $products = $featuredProducts;
        $sectionTitle = "Featured Products";
        require base_path("views/partials/product-row.php"); ?>
    </div>

    <!-- Discounted Products Row -->
    <div class="centre-content featured-products-area">
        <?php
        $products = $discountedProducts;
        $sectionTitle = "On Promotion";
        require base_path("views/partials/product-row.php"); ?>
    </div>

    <!-- Top Rated Products Row -->
    <div class="centre-content featured-products-area">
        <?php
        $products = $topRatedProducts;
        $sectionTitle = "Top rated";
        require base_path("views/partials/product-row.php"); ?>
    </div>

</main>

<?php require base_path("views/partials/footer.php"); ?>