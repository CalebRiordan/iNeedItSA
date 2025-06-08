<a class="product-card <?= $cardWidthClass ?? '' ?>" href="/products/<?= $product->id ?>">

  <div class="image-container">
    <?php if ($product->discount > 0): ?>
      <span class="discount-badge"><b>-</b><?= $product->discount ?>%</span>
    <?php endif; ?>
    <img
      src="<?= $product->displayImageUrl ? htmlspecialchars($product->displayImageUrl) : '/assets/images/product-placeholder.png' ?>"
      alt="<?= htmlspecialchars($product->name) ?>" />
  </div>

  <div class="name">
    <p> <?= htmlspecialchars($product->name) ?></p>
  </div>

  <div class="desc">
    <p><?= !empty($showDesc) ? htmlspecialchars($product->description) : "" ?></p=>
  </div>

  <div class="bottom-row">
    <?php if ($product->discount > 0): ?>
      <span class="original-price">R<?= number_format($product->price, 0) ?></span>
      <h4 class="price">
        R<?= number_format($product->price * ((100 - $product->discount) / 100), 0) ?>
      </h4>
    <?php else: ?>
      <h4 class="price">R<?= number_format($product->price, 0) ?></h4>
    <?php endif; ?>

    <?php if ($product->rating): ?>
      <div class="rating">
        <h4><?= $product->rating ?></h4>
        <svg class="star" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="30" height="25">
          <path d="M12 .587l3.668 7.429L23.334 9.26l-5.667 5.528L18.5 23.41 12 19.577 5.5 23.41 6.333 14.788.667 9.26l7.666-1.244L12 .587z"
            stroke="black"
            stroke-width="0.7" />
        </svg>
      </div>
    <?php endif; ?>

  </div>

</a>