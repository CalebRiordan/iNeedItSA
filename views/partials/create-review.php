<form class="create-review <?= !$user ? 'blur' : '' ?>" action="/products/<?= $product->id ?>/review" method="POST">
    <div class="review-header">
        <!-- Rating selection -->
        <div class="star-rating">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <span class="star" data-value="<?= $i ?>">☆</span>
            <?php endfor; ?>
        </div>
        <input id="rating" name="rating" type="hidden" value="0">
    </div>

    <!-- Review box -->
    <div class="review-box">
        <textarea id="comment" placeholder="Comment" name="comment" rows="3" maxlength="255"></textarea>
    </div>

    <p class="error error-rating"><?= $errors['rating'] ?? "" ?></p>

    <button type='submit' class="disabled">Submit</button>
</form>