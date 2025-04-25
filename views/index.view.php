<?php
$stylesheets = ['navbar.css', 'category-bar.css', 'products-search-bar.css', 'index.css'];
$scripts = ['products-search-bar.js'];

require base_path("views/partials/header.php");
require base_path("views/partials/navbar.php");
require base_path("views/partials/category-bar.php");
?>

<main>
    <div class="centre search-bar-area">
        <?php require base_path("views/partials/products-search-bar.php"); ?>
    </div>
</main>
<?php require base_path("views/partials/footer.php"); ?>