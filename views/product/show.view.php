<?php

use Core\Session;

$stylesheets = ['category-bar.css', 'product/show.css', 'products-search-bar.css'];
$scripts = ['product/show.js', 'products-search-bar.js'];
$user = Session::get('user');

require partial('header');
require partial('navbar');
require partial('category-bar');
?>

<input id="product-id" value="<?= $product->id ?>" type="hidden">
<input id="product-price" value="<?= $product->price ?>" type="hidden">

<main>
    <!-- Search Bar -->
    <div class="centre-content search-bar-area">
        <?php require partial('products-search-bar'); ?>
    </div>

    <!-- Product Information Section -->
    <div class="content-column">

        <!-- Heading -->
        <div class="heading">
            <h1><?= htmlspecialchars($product->name) ?></h1>

            <div class="pricing">

                <?php if ($product->discount > 0): ?>
                    <span class="original-price">R<?= number_format($product->price, 0) ?></span>
                    <h2 class="price">
                        R<?= number_format($product->price * ((100 - $product->discount) / 100), 0) ?>
                    </h2>
                    <div class="discount-badge"><span>-</span><?= $product->discount ?>%</div>
                <?php else: ?>
                    <h2 class="price">R<?= number_format($product->price, 0) ?></h2>
                <?php endif; ?>
            </div>
        </div>

        <!-- Images & Seller Card -->
        <div class="main-grid">
            <img
                src="<?= $product->displayImageUrl ? htmlspecialchars($product->displayImageUrl) : '/assets/images/product-placeholder.png' ?>"
                alt="<?= htmlspecialchars($product->name) ?>" />

            <div class="seller-card-wrapper">
                <?php require partial('product-action-buttons') ?>
                <?php require partial('seller-card') ?>
            </div>

            <div class="image-list">
            </div>
        </div>

        <!-- Product Info -->
        <div class="product-info-table">
            <div class="product-description">
                <div class="top-row">
                    <strong><?= $product->views ?> Views</strong>
                    &nbsp;
                    <?= elapsedTimeString(new DateTime($product->dateCreated)); ?>
                    &nbsp;
                    <button id='share-btn' title='Copy link'>
                        <div id="copied-popup">
                            Link Copied!
                        </div>

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                            <path d="M352 224c53 0 96-43 96-96s-43-96-96-96s-96 43-96 96c0 4 .2 8 .7 11.9l-94.1 47C145.4 170.2 121.9 160 96 160c-53 0-96 43-96 96s43 96 96 96c25.9 0 49.4-10.2 66.6-26.9l94.1 47c-.5 3.9-.7 7.8-.7 11.9c0 53 43 96 96 96s96-43 96-96s-43-96-96-96c-25.9 0-49.4 10.2-66.6 26.9l-94.1-47c.5-3.9 .7-7.8 .7-11.9s-.2-8-.7-11.9l94.1-47C302.6 213.8 326.1 224 352 224z" />
                        </svg>
                    </button>
                </div>

                <h3>Description</h3>
                <p><?= $product->description ?></p>
            </div>

            <table class="product-details">
                <tr>
                    <td class="name">Condition</td>
                    <td><?= $product->condition ?></td>
                </tr>
                <tr>
                    <td class="name">Condition Details</td>
                    <td><?= $product->conditionDetails ?? "-" ?></td>
                </tr>
                <tr>
                    <td class="name">Rating</td>
                    <td><?= $product->rating ?? "<i>Not yet rated</i>" ?></td>
                </tr>
                <tr>
                    <td class="name">Category</td>
                    <td><?= $product->category ?></td>
                </tr>

            </table>
        </div>

        <div class="seller-card-wrapper card-mobile">
            <?php require partial('product-action-buttons') ?>
            <?php require partial('seller-card') ?>
        </div>

        <!-- Reviews -->
        <br>
        <h1 class="reviews-heading">Reviews</h1>

        <!-- Create review -->
        <!-- Simply do not show review box if user has not bought product yet -->
        <?php if (!$userReview): ?>
            <?php if ($userHasBoughProduct): ?>
                <div class="create-review-section">
                    <?php require partial('create-review') ?>

                    <?php if (!$user): ?>
                        <div class="review-locked">
                            <p class="review-locked-text"><a href="/login">Log in</a> to review products</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="centre-content">
                    <p style="margin-bottom: 0;">🔒<em>Purchase this item to review it</em></p>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- List of reviews -->
        <div id="reviews">
            <?php
            // User's own review shows first
            if ($userReview) {
                $review = $userReview;
                require partial('review');
            }
            ?>

            <!-- Reviews from other users -->
            <?php
            foreach ($reviews as $review) {
                require partial('review');
            }
            ?>

            <?php if (empty($reviews) && !$userReview): ?>
                <div class="centre-content">
                    <h2 style="font-weight: normal">🦗 No one has reviewed this product yet</h2>
                </div>
            <?php endif ?>
        </div>
    </div>
</main>

<?php require partial('toast'); ?>
<?php require partial('footer'); ?>