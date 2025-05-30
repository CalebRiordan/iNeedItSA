<?php

// Use query string to differentiate purpose of request

use Core\Repositories\OrderRepository;
use Core\Session;

$view = $params['view'] ?? null;
if ($view === "cart") {
    redirect("/order#cart");
} elseif ($view === "orders") {
    redirect("/order#history");
}

$orders = (new OrderRepository())
    ->findByUser(Session::get('user')['id']);

view("order/index", ['orders' => $orders]);
