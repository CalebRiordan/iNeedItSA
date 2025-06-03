<?php

use Core\Repositories\CartRepository;
use Core\Session;

$input = json_decode(file_get_contents('php://input'), true);
$cart = $input['items'] ?? null;
if (!$cart || !is_array($cart)) {
    response(['error' => 'Invalid cart data'], 400);
}

Session::put('cart', $cart);
$userId = Session::get('user')['id'];

// Save cart to database
(new CartRepository())->persist($userId, $cart);
// dd("After cart repo persist");

response(['message' => 'Cart saved successfully']);