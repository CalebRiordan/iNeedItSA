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
            <a href="/products?seller=<?= $product->seller->id ?>">View Seller’s Products</a>
        </div>
    </div>
</div>