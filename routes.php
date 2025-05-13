<?php

$router->get("/", "index.php");
$router->get("/home", "index.php");

$router->get("/products", "products/index.php");
$router->get("/products/{id}", "products/show.php");

$router->partial("/products-display");