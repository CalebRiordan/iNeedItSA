<?php $userIsOwner = $review->userId === $userReview->userId ?? null ?>

<div class="review">
    <div class="review-header <?= $userIsOwner ? 'review-owner' : ''?>">
        <!-- Name & Date -->
        <strong class="name"><?php echo htmlspecialchars($review->userFirstName . ' ' . $review->userLastName) . ($userIsOwner ? '<em> (You)</em>' : ''); ?></strong>
        <span class="review-date"><?php echo htmlspecialchars($review->date); ?></span>

        <?php if ($userIsOwner): ?>
            <!-- Delete icon -->
            <span id="delete-review" class="action-btn" data-value="<?= $review->id ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z" />
                </svg>
            </span>
        <?php endif; ?>

        <!-- Star rating -->
        <span class="review-rating"><?php echo str_repeat('★', (int)$review->rating) . str_repeat('☆', 5 - (int)$review->rating); ?></span>
    </div>
    <div class="review-comment">
        <?php echo htmlspecialchars($review->comment); ?>
    </div>
</div>