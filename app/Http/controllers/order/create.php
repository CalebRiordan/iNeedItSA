<?php

use Core\Repositories\UserRepository;
use Core\Session;

$cart = Session::get('cart');

if (!$cart) {
    redirect("/order");
}

$total = 0;
$itemsCount = 0;
foreach ($cart as $item) {
    $itemsCount += $item['quantity'];
    $total += $item['quantity'] * $item['price'];
}

$userId = Session::get('user')['id'];
$user = (new UserRepository())->findById($userId);

view('order/create', ['total' => $total, 'itemsCount' => $itemsCount, 'user' => $user]);