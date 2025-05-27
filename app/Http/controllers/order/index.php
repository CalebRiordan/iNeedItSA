<?php

// Use query string to differentiate purpose of request
$view = $params['view'] ?? null;
if ($view === "cart") {
    redirect("/orders#cart");
} elseif ($view === "orders") {
    redirect("/orders#orders");
}

// Get cart info

view("order/index");