<?php

$router->get("/", "index.php");
$router->get("/home", "index.php");

$router->get("/products", "products/index.php");

$router->api()->get("/products", "ProductController.php");