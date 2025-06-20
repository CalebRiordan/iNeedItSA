<?php

use Core\Repositories\ProductRepository;
use Core\Repositories\ReviewRepository;
use Core\Session;

$id = $params['id'] ?? null;
if (!$id) abort(404);

// Get product
$products = new ProductRepository();
$product = $products->findById($id);

// Get reviews
$reviews = (new ReviewRepository())->findByProduct($id);
$userId = Session::get('user')['id'] ?? '';

// Find and remove user's review from the list
$userReview = null;
$filteredReviews = [];
foreach ($reviews as $review) {
    if ($review->userId === $userId) {
        $userReview = $review;
    } else {
        $filteredReviews[] = $review;
    }
}
$reviews = $filteredReviews;

// User can only review product if they have bought it
$userHasBoughProduct = $userId !== '' || $products->hasBeenBoughtBy($id, $userId);

view("product/show", [
    "product" => $product,
    "reviews" => $reviews,
    "userReview" => $userReview,
    "userHasBoughProduct" => $userHasBoughProduct
]);
