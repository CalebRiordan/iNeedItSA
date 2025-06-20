<div class="review">
    <div class="review-header">
        <strong><?php echo htmlspecialchars($review->userFirstName . ' ' . $review->userLastName); ?></strong>
        <span class="review-date"><?php echo htmlspecialchars($review->date); ?></span>
        <span class="review-rating"><?php echo str_repeat('★', (int)$review->rating) . str_repeat('☆', 5 - (int)$review->rating); ?></span>
    </div>
    <div class="review-comment">
        <?php echo htmlspecialchars($review->comment); ?>
    </div>
</div>