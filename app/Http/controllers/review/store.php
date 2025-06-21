<?php

use Core\DTOs\CreateReviewDTO;
use Core\Repositories\ProductRepository;
use Core\Repositories\ReviewRepository;
use Core\Session;
use Core\ValidationException;

$productId = $_POST['product'] ?? null;
$rating = $_POST['rating'] ?? 0;
$comment = $_POST['comment'] ?? null;
$userId = Session::get('user')['id'];

if ($rating < 1 || $rating > 5) {
    ValidationException::throw(['rating' => 'Rating must be between 1 and 5'], []);
}

// Check that user has already bought product and not reviewed it yet 
$hasbeenBought = (new ProductRepository())->hasBeenBoughtBy($productId, $userId);
$newReview = new CreateReviewDTO($userId, $productId, $rating, $comment);
$hasbeenReviewed = !(new ReviewRepository())->create($newReview);

if (!$hasbeenBought || $hasbeenReviewed) {
    abort(403);
}

redirect("/products/{$productId}#reviews");
