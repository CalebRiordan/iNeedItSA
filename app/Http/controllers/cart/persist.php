<?php

use Core\Session;

response(["persist"]);
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['items']) || !is_array($input['items'])) {
    response(['error' => 'Invalid cart data'], 400);
}

Session::put('cart', $input['items']);

response(['message' => 'Cart saved successfully']);