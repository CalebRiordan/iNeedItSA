<?php

use Core\Repositories\CartRepository;
use Core\Session;

$input = json_decode(file_get_contents('php://input'), true);

// Must not fail with empty array
if (!isset($input['items']) || !is_array($input['items'])) {
    response(['error' => 'Invalid cart data'], 400);
}
$cart = $input['items'];

$userId = Session::get('user')['id'];

// Save cart to database - get updated cart with latest prices
$cart = (new CartRepository())->persist($userId, $cart);
if ($cart === null) {
    response(['error' => 'Invalid cart data'], 400);
}

// Save cart to session
Session::put('cart', $cart);

response(['message' => 'Cart saved successfully']);