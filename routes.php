<?php

$router->get("/", "index.php");
$router->get("/home", "index.php");

$router->get("/products", "products/index.php");
$router->get("/products/{id}", "products/show.php");

$router->get("/login", "session/create.php")->only('guest');
$router->post("/login", "session/store.php")->only('guest');
$router->get("/logout", "session/destroy.php")->only('auth');
$router->get("/register", "registration/create.php")->only('guest');
$router->post("/register", "registration/store.php")->only('guest');
$router->get("/profile/edit", "registration/edit.php")->only('auth');
$router->put("/profile", "registration/update.php")->only('auth');

$router->partial("/products-display");