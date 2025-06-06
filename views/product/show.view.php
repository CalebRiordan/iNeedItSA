<?php

use Core\Session;

$stylesheets = ['category-bar.css', 'product/show.css', 'products-search-bar.css'];
$scripts = ['product/show.js', 'products-search-bar.js'];

require partial('header');
require partial('navbar');
require partial('category-bar');
?>

<input id="product-id" value="<?= $product->id ?>" type="hidden">
<input id="product-price" value="<?= $product->price ?>" type="hidden">

<main>
    <!-- Search Bar -->
    <div class="centre-content search-bar-area">
        <?php require base_path("views/partials/products-search-bar.php"); ?>
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

        <!-- Images & Seller Info -->
        <div class="main-grid">
            <img
                src="<?= $product->displayImageUrl ? htmlspecialchars($product->displayImageUrl) : '/assets/images/product-placeholder.png' ?>"
                alt="<?= htmlspecialchars($product->name) ?>" />

            <div class="col-right">
                <a class="add-cart-btn salient" data-restricted="<?= !Session::has('user') ? true : false ?>">
                    <div id="item-not-added" class="content">
                        <span>Add to Cart</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                            <path d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96zM252 160c0 11 9 20 20 20l44 0 0 44c0 11 9 20 20 20s20-9 20-20l0-44 44 0c11 0 20-9 20-20s-9-20-20-20l-44 0 0-44c0-11-9-20-20-20s-20 9-20 20l0 44-44 0c-11 0-20 9-20 20z" />
                        </svg>
                    </div>

                    <div id="item-added" class="content" hidden>
                        <span>Added to Cart!</span>
                    </div>

                    <div class="content">
                        <span class="loading" hidden></span>
                    </div>
                </a>

                <div class="seller-card">
                    <div class="header">
                        <h1><?= $product->seller->firstName . " " . $product->seller->lastName ?></h1>
                        <?php if ($product->seller->sellerProfile->isVerified): ?>
                            <div class="verified">
                                <span class="tag">Verified</span><br>
                                <span>since <?= $product->seller->sellerProfile->dateVerified ?? "" ?></span>
                            </div>
                        <?php else: ?>
                            <div class="verified">
                                <span>Not verified</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="details">
                        <p>Seller Since <strong><?= date('Y', strtotime($product->seller->sellerProfile->dateRegistered)) ?></strong></p>

                        <div class="aligned-grid">

                            <span class="stat-value"><?= $product->seller->sellerProfile->productsSold ?></span>
                            <span class="stat-name">Products Sold</span>
                            <span class="stat-value">
                                <?php
                                $views = $product->seller->sellerProfile->views;
                                echo $views < 1000 ? $views : number_format($views / 1000, 1) . 'k' ?>
                            </span>
                            <span class="stat-name">Views</span>

                        </div>

                        <div class="location">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                <path d="M192 0C86 0 0 86 0 192c0 77.4 106.1 215.6 167.4 284.5 9.6 10.9 26.7 10.9 36.3 0C277.9 407.6 384 269.4 384 192 384 86 298 0 192 0zm0 272c-44.2 0-80-35.8-80-80s35.8-80 80-80 80 35.8 80 80-35.8 80-80 80z" />
                            </svg>
                            <?= $product->seller->location ?>, <?= $product->seller->province ?>
                        </div>

                        <div class="seller-products-link">
                            <a href="#">View Seller’s Products</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="image-list">
                Image List
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
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                            <path d="M352 224c53 0 96-43 96-96s-43-96-96-96s-96 43-96 96c0 4 .2 8 .7 11.9l-94.1 47C145.4 170.2 121.9 160 96 160c-53 0-96 43-96 96s43 96 96 96c25.9 0 49.4-10.2 66.6-26.9l94.1 47c-.5 3.9-.7 7.8-.7 11.9c0 53 43 96 96 96s96-43 96-96s-43-96-96-96c-25.9 0-49.4 10.2-66.6 26.9l-94.1-47c.5-3.9 .7-7.8 .7-11.9s-.2-8-.7-11.9l94.1-47C302.6 213.8 326.1 224 352 224z" />
                        </svg>
                    </a>
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

        <!-- Reviews -->
        <?php if (Session::has('user')): ?>
            <div class="create-comment"></div>
        <?php endif; ?>

        <div class="comments-list">

        </div>
    </div>
</main>

<?php require partial('footer'); ?>