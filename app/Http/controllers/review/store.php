<?php

use Core\DTOs\CreateReviewDTO;
use Core\Repositories\ProductRepository;
use Core\Repositories\ReviewRepository;
use Core\Session;
use Core\ValidationException;

$productId = $params['id'];
$rating = $_POST['rating'] ?? 0;
$comment = $_POST['comment'] ?? null;
$userId = Session::get('user')['id'];

if ($rating < 1 || $rating > 5) {
    ValidationException::throw(['rating' => 'Rating must be between 1 and 5'], []);
}

// Check that user has not already bought product
if ((new ProductRepository())->hasBeenBoughtBy($productId, $userId)) {
    abort(403);
}

$newReview = new CreateReviewDTO($userId, $productId, $rating, $comment);
if (!(new ReviewRepository())->create($newReview)) {
    abort(500);
}

redirect("/products/{$productId}");
