<?php

$router->get("/", "index.php");
$router->get("/home", "index.php");

$router->get("/products", "products/index.php");
$router->get("/products/{id}", "products/show.php");

$router->get("/login", "session/create.php");
$router->post("/login", "session/store.php");
$router->get("/logout", "session/destroy.php");
$router->get("/register", "registration/create.php");
$router->post("/register", "registration/store.php");

$router->partial("/products-display");