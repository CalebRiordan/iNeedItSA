<?php

$stylesheets = ['navbar.css', 'category-bar.css', 'products/index.css'];
$scripts = ['products-search-bar.js'];

require base_path('views/partials/header.php');
require base_path('views/partials/navbar.php');
require base_path('views/partials/category-bar.php');
?>

<main>
    <div class="filter-panel">


        <div class="search-bar">

            <div class="search-field">
                <input type="text" maxlength="80" placeholder="What are you looking for?" value=<?= $_GET['search'] ?>>
            </div>

            <div class="search-button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
                </svg>
            </div>

        </div>


        <a class="apply-filter-btn" href="">
            Apply Filter
        </a>

        <div class="price-section">
            <h1>Price</h1>
            <div class="price-options">

                <div class="range-selector">
                    <label for="input-min">Min</label>
                    <div>
                        <span class="rand-symbol">R</span>
                        <input
                            id="input-min"
                            type="text"
                            placeholder="0"
                            pattern="^\d{0,7}$"
                            title="Please enter a number between 0 and 7 digits">
                    </div>
                </div>

                <div class="range-selector">
                    <label for="input-max">Max</label>
                    <div>
                        <span class="rand-symbol">R</span>
                        <input
                            id="input-max"
                            type="text"
                            placeholder="any"
                            pattern="^\d{0,7}$"
                            title="Please enter a number between 0 and 7 digits">
                    </div>
                </div>

            </div>
        </div>

        <div class="category-section">
            <h1>Category</h1>
        </div>

        <div class="rating-section">
            <h1>Rating</h1>
        </div>

    </div>

    <div class="products-catalog">

    </div>

</main>

<?php require base_path('views/partials/footer.php'); ?>