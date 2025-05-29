<a class="product-card" style="width: <?= isset($cardWidth) ? $cardWidth : '18%' ?>;" href="/products/<?= $product->id ?>">

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
  </div>

</a>