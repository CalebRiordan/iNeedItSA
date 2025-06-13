<?php

use Core\DTOs\CreateProductDTO;
use Core\Repositories\ProductRepository;
use Core\Session;
use Http\Forms\ProductForm;

$fields = [
    "name" => htmlspecialchars($_POST["name"], ENT_QUOTES),
    "description" => htmlspecialchars($_POST["description"], ENT_QUOTES),
    "price" => htmlspecialchars($_POST["price"], ENT_QUOTES),
    "stock" => htmlspecialchars($_POST["stock"], ENT_QUOTES),
    "condition" => htmlspecialchars($_POST["condition"], ENT_QUOTES),
    "condition_details" => htmlspecialchars($_POST["condition_details"], ENT_QUOTES),
    "discount" => htmlspecialchars($_POST["discount"], ENT_QUOTES),
    "category" => htmlspecialchars($_POST["category"], ENT_QUOTES),
    "product_img" => noImageUploaded($_FILES["product_img"]) ? null : $_FILES["product_img"],
];

// Server-side form validation
$form = ProductForm::validate($fields);

$products = new ProductRepository();
$existingProduct = $products->findByName($fields['name']);

if ($existingProduct) {
    $form->error('name', 'A product with this name already exists. Please choose another')->throw();
}

$sellerId = Session::get('user')['id'];

$product = new CreateProductDTO(
    $fields["name"],
    $fields["description"],
    $fields["price"],
    $sellerId,
    $fields["stock"],
    $fields["condition"],
    $fields["condition_details"],
    $fields["discount"],
    $fields["category"],
    $fields["product_img"]
);

$newProduct = $products->create($product);

if (!$newProduct) abort(500);

Session::toast("Your new product has been listed!");
redirect('/seller/dashboard');
