<?php

use Core\Repositories\OrderRepository;

$id = $params["id"] ?? null;

if (!$id) {
    response(['error' => "Invalid input. No 'items' key found for cart items."], 400);
}

try {
    $orders = (new OrderRepository())->findItemsById($id);
} catch (\Throwable $th) {
    response(["error" => "Error retrieving orders"], 500);
}

response($orders);
