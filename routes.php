<?php

$router->get("/", "index.php");
$router->get("/home", "index.php");

$router->get("/products", "product/index.php");
$router->get("/products/{id}", "product/show.php");

$router->get("/login", "session/create.php")->only('guest');
$router->post("/login", "session/store.php")->only('guest');
$router->get("/logout", "session/destroy.php")->only('auth');
$router->get("/register", "user/create.php")->only('guest');
$router->post("/register", "user/store.php")->only('guest');
$router->get("/profile/edit", "user/edit.php")->only('auth');
$router->put("/profile", "user/update.php")->only('auth');

$router->get("/order", "order/index.php")->only('auth');
$router->get("/checkout", "order/create.php")->only('auth');
$router->post("/checkout", "order/store.php")->only('auth');

$router->get("/cart", "cart/get.php")->only('auth');
$router->post("/cart", "cart/persist.php")->only('auth');

$router->get("/500", "500.php");

$router->partial("/products-display");
$router->partial("/cart", "POST")->only('auth');
$router->partial("/order/{id}")->only('auth');