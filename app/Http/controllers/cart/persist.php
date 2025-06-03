<?php

use Core\Repositories\CartRepository;
use Core\Session;

$input = json_decode(file_get_contents('php://input'), true);
$cart = $input['items'] ?? null;

if (!$cart || !is_array($cart)) {
    response(['error' => 'Invalid cart data'], 400);
}

$userId = Session::get('user')['id'];

// Save cart to database
if (!(new CartRepository())->persist($userId, $cart)) {
    response(['error' => 'Invalid cart data'], 400);
}

// Save cart to session
Session::put('cart', $cart);

response(['message' => 'Cart saved successfully']);