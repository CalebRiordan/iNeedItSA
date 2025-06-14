<?php


use Core\Repositories\OrderRepository;
use Core\Session;

// Use query string to differentiate purpose of request
$view = $params['view'] ?? null;
if ($view === "cart") {
    redirect("/order#cart");
} elseif ($view === "orders") {
    redirect("/order#history");
}

$id = Session::get('user')['id'];
$orders = (new OrderRepository())->findByUser($id);

view("order/index", ['orders' => $orders]);
