<?php

$router->get("/", "index.php");
$router->get("/home", "index.php");

// Products
$router->get("/products", "product/index.php");
$router->get("/products/new", "product/create.php")->only('seller');
$router->post("/products", "product/store.php")->only('seller');
$router->put("/products", "product/update.php")->only('seller');
$router->get("/products/edit/{id}", "product/edit.php")->only('seller');
$router->get("/products/{id}", "product/show.php");
$router->delete("/products/{id}", "product/destroy.php")->only('seller');
$router->put("/products/{id}", "product/update.php")->only('seller');

// User
$router->get("/login", "session/create.php")->only('guest');
$router->post("/login", "session/store.php")->only('guest');
$router->get("/logout", "session/destroy.php")->only('auth');
$router->get("/register", "user/create.php")->only('guest');
$router->post("/register", "user/store.php")->only('guest');
$router->get("/profile/edit", "user/edit.php")->only('auth');
$router->put("/profile/{id}", "user/update.php")->only('auth');

// Orders
$router->get("/order", "order/index.php")->only('auth');
$router->get("/order/success", "order/success.php")->only('auth');
$router->get("/checkout", "order/create.php")->only('auth');
$router->post("/checkout", "order/store.php")->only('auth');

// Cart
$router->get("/cart", "cart/get.php")->only('auth');
$router->post("/cart", "cart/persist.php")->only('auth');

// Seller
$router->get("/seller/register", "seller/create.php")->only('auth');
$router->post("/seller/register", "seller/store.php")->only('auth');
$router->get("/seller/dashboard", "seller/index.php")->only('seller');

// Status codes
$router->get("/500", "500.php");

// Admin dashboard
$router->get("/admin", "admin/index.php")->only('staff');
$router->get("/admin/login", "admin/session/create.php")->deny('staff');
$router->post("/admin/login", "admin/session/store.php")->deny('staff');
$router->get("/admin/logout", "admin/session/destroy.php")->only('staff');
$router->get("/admin/enrol", "admin/staff/create.php")->only('staff');
$router->post("/admin/login", "admin/staff/store.php")->only('staff');
$router->get("/admin/reports", "admin/reports/index.php")->only('staff');
$router->get("/admin/sellers/pending", "admin/seller_reg/show.php")->only('staff');
$router->post("/admin/sellers/registration", "admin/seller_reg/edit.php")->only('staff');

// Partials
$router->partial('/products-display');
$router->partial('/cart', 'POST')->only('auth');
$router->partial('/order/{id}')->only('auth');