<?php

$stylesheets = ['category-bar.css', 'product/index.css', 'product-card.css'];
$scripts = ['product/index.js', 'utils/scaleProductCardFont.js'];

require partial('header');
require partial('navbar');
require partial('category-bar');
?>

<main>
    <div class="products-content-wrapper" data-params='<?= htmlspecialchars(json_encode(array_merge($params, ['products-display' => $productsDisplay])), ENT_QUOTES, 'UTF-8'); ?>'>
        <!-- 'Open filter' button - only displayed on mobile -->
        <div id="filter-btn-mobile">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path d="M0 416c0 17.7 14.3 32 32 32l54.7 0c12.3 28.3 40.5 48 73.3 48s61-19.7 73.3-48L480 448c17.7 0 32-14.3 32-32s-14.3-32-32-32l-246.7 0c-12.3-28.3-40.5-48-73.3-48s-61 19.7-73.3 48L32 384c-17.7 0-32 14.3-32 32zm128 0a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zM320 256a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zm32-80c-32.8 0-61 19.7-73.3 48L32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l246.7 0c12.3 28.3 40.5 48 73.3 48s61-19.7 73.3-48l54.7 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-54.7 0c-12.3-28.3-40.5-48-73.3-48zM192 128a32 32 0 1 1 0-64 32 32 0 1 1 0 64zm73.3-64C253 35.7 224.8 16 192 16s-61 19.7-73.3 48L32 64C14.3 64 0 78.3 0 96s14.3 32 32 32l86.7 0c12.3 28.3 40.5 48 73.3 48s61-19.7 73.3-48L480 128c17.7 0 32-14.3 32-32s-14.3-32-32-32L265.3 64z" />
            </svg>
        </div>

        <!-- Filter panel -->
        <div class="filter-panel">
            <div id="close-filter-btn-mobile">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                    <path d="M376.6 84.5c11.3-13.6 9.5-33.8-4.1-45.1s-33.8-9.5-45.1 4.1L192 206 56.6 43.5C45.3 29.9 25.1 28.1 11.5 39.4S-3.9 70.9 7.4 84.5L150.3 256 7.4 427.5c-11.3 13.6-9.5 33.8 4.1 45.1s33.8 9.5 45.1-4.1L192 306 327.4 468.5c11.3 13.6 31.5 15.4 45.1 4.1s15.4-31.5 4.1-45.1L233.7 256 376.6 84.5z" />
                </svg>
            </div>

            <div class="search-bar">

                <div class="search-field">
                    <input type="text" maxlength="80" placeholder="What are you looking for?" value=<?= $params['search'] ?? "" ?>>
                </div>

                <div class="search-button disabled">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
                    </svg>
                </div>

            </div>

            <button class="apply-filter-btn disabled" submit="">
                Apply Filter
            </button>

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
                                title="Please enter a number between 0 and 7 digits"
                                value="<?= $params['minPrice'] ?? '' ?>">
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
                                title="Please enter a number between 0 and 7 digits"
                                value="<?= $params['maxPrice'] ?? '' ?>">
                        </div>
                    </div>

                </div>
            </div>

            <div class="category-section">
                <h1>Category</h1>
                <div class="category-list">
                    <?php $categoryIndex = $params['category'] ?? null ?>
                    <?php for ($i = 0; $i <= 7; $i++): ?>
                        <div class="option <?= $categoryIndex === $i ? 'active' : '' ?>" data-value="<?= $i ?>">
                            <?= ["Clothing", "Electronics", "Home & Garden", "Books & Stationery", "Toys", "Beauty", "Sports", "Pets"][$i] ?>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="rating-section">
                <h1>Rating</h1>

                <div class="star-rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <div class="star-wrapper">
                            <svg class="star" data-value="<?= $i ?>" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="30" height="25">
                                <path d="M12 .587l3.668 7.429L23.334 9.26l-5.667 5.528L18.5 23.41 12 19.577 5.5 23.41 6.333 14.788.667 9.26l7.666-1.244L12 .587z"
                                    stroke="black"
                                    stroke-width="0.7" />
                            </svg>
                            <div><?= $i ?></div>
                        </div>
                    <?php endfor; ?>
                </div>
                <label id="rating-label"></label>
            </div>

        </div>

        <!-- Products Catalogue -->
        <div class="products-catalogue">
            <h1 id="results-header"><?= isset($params['search']) ? "Results for <span>\"{$params['search']}\"</span>" : "" ?></span></h1>
            <div class="products-grid">
                <!-- productsDisplay partial - passed from controller and replaced via AJAX -->
                <?= $productsDisplay ?>
            </div>

            <div class="no-results centre-content">
                <img src="/assets/images/no-results-found.png" alt="Sorry, no results found.">
            </div>
        </div>
    </div>

    <div class="centre-content">
        <div class="page-selector">
            <?= $pageSelector ?>
        </div>
    </div>

</main>

<?php require partial('footer'); ?>