<?php

use Core\Repositories\ReviewRepository;

$reviewId = $params['id'] ?? null;

if (!$reviewId) response(['error' => 'Review not found.'], 404);

// Delete review
if (!(new ReviewRepository())->delete($reviewId)) {
    response(['error' => 'Review not found.'], 404);
}

response(["success" => "Review deleted successfully"]);