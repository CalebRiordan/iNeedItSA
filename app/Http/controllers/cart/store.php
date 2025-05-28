<?php

use Core\Session;

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['items']) || !is_array($input['items'])) {
    response(400, ['error' => 'Invalid cart data']);
}

Session::put('cart', $input['items']);

returnJson(['message' => 'Cart saved successfully']);