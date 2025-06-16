<?php

use Core\DTOs\CreateProductDTO;
use Core\Repositories\ProductRepository;
use Core\Session;
use Http\Forms\Form;
use Http\Forms\ProductForm;

$fields = [
    'name' => Form::getField('name'),
    'description' => Form::getField('description'),
    'price' => Form::getNumericField('price'),
    'stock' => Form::getNumericField('stock'),
    'condition' => Form::getField('condition'),
    'conditionDetails' => Form::getField('condition_details'),
    'discount' => Form::getNumericField('discount'),
    'category' => Form::getNumericField('category'),
    'displayImageFile' => Form::getImageField('product_img'),
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
    $fields['name'],
    $fields['description'],
    $fields['price'],
    $sellerId,
    $fields['stock'],
    $fields['condition'],
    $fields['conditionDetails'],
    $fields['discount'],
    $fields['category'],
    $fields['displayImageFile']
);

$newProduct = $products->create($product);

if (!$newProduct) abort(500);

Session::toast("Your new product has been listed!");
$path = $auth->pm() ? '/' : '/seller/dashboard';
redirect($path);
