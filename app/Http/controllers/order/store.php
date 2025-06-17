<?php

use Core\DTOs\CreateOrderDTO;
use Core\DTOs\CreateOrderItemDTO;
use Core\Repositories\CartRepository;
use Core\Repositories\OrderRepository;
use Http\Forms\CheckoutForm;
use Core\Session;

$csrfToken = Session::get('csrf_token');
if ($csrfToken && !hash_equals($csrfToken, $_POST['csrf_token'] ?? '')) {
    abort(403);
}

$address = $_POST['address'];
$cardNumber = $_POST['card_num'];
$csvCode = $_POST['csv_code'];

CheckoutForm::validate(['address' => $address, 'card_num' => $cardNumber, 'csv_code' => $csvCode]);

$cart = Session::get('cart');

if (!$cart) {
    redirect("/order");
}

// Get order details
$user = Session::get('user');
$userId = $user['id'];
$location = $user['location'];

// Get order data from cart
$total = 0;
$itemsCount = 0;
$items = [];
foreach ($cart as $item) {
    $qty = $item['quantity'];
    $itemsCount += $qty;
    $total += $qty * $item['price'];

    // Create order item DTOs
    $items[] = new CreateOrderItemDTO($item['product_id'], $qty, $item['price']);
}
$total += $total > 1000 ? 40 : 75; 

$newOrder = new CreateOrderDTO($userId, $address, $location, $total, $items);

// Store new order in database
try {
    $newId = (new OrderRepository())->create($newOrder);
} catch (\Throwable $th) {
    Session::toast("Something went wrong while processing the order. Please try again later, or contact support", "error");
    redirect("/checkout");
}

Session::put('last_order_processed', [
    'order_id' => $newId,
    'timestamp' => time(),
]);

// Clear cart & redirect
(new CartRepository())->deleteByUser($userId);
Session::remove('cart');

redirect("/order/success");
