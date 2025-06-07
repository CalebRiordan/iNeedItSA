<?php

$stylesheets = ['category-bar.css', 'product/index.css', 'product-card.css'];
$scripts = ['product/index.js', 'utils/scaleProductCardFont.js'];

$cardWidth = 'auto';

require partial('header');
require partial('navbar');
require partial('category-bar');
?>

<main>
    <div class="products-content-wrapper" data-params='<?= htmlspecialchars(json_encode(array_merge($params, ['products-display' => $productsDisplay])), ENT_QUOTES, 'UTF-8'); ?>'>
        <div class="filter-panel">


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

        <div class="products-catalogue">
            <h1 id="results-header"><?= isset($params['search']) ? "Results for <span>\"{$params['search']}\"</span>" : "" ?></span></h1>
            <div class="products-grid">
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