<?php

$router->get("/", "index.php");
$router->get("/home", "index.php");

$router->get("/products", "products/index.php");
$router->get("/products/{id}", "products/show.php");

$router->get("/login", "login/create.php");
$router->post("/login", "login/store.php");

$router->partial("/products-display");

