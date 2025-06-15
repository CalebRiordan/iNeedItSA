<?php

use Core\Repositories\ProductRepository;
use Core\Session;

$id = $params['id'] ?? null;

if (!$id) {
    response(["error" => "Invalid input - 'id' is required"], 400);
}

$products = new ProductRepository();
$userId = Session::get('user')['id'];

// Product doesn't belong to this seller -> 404 Not Found (obscurity)
if (!$products->isFromSeller($id, $userId)) abort();

if (!$products->delete($id)) {
    response(["error" => "Product could not be deleted from database"], 500);
}

response(['message' => 'Product deleted.']);