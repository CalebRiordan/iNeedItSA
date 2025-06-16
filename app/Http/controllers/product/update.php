<?php

use Core\Authorizor;
use Core\DTOs\UpdateProductDTO;
use Core\Repositories\ProductRepository;
use Core\Session;
use Http\Forms\EditProductForm;
use Http\Forms\Form;

$id = $params['id'];
$products = new ProductRepository();
$userId = Session::get('user')['id'];

// Product doesn't belong to this seller -> 404 Not Found (obscurity)
$auth = new Authorizor();
if (!$auth->ownsProduct($id)) abort();

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
    'imageChanged' => Form::getBoolField('image_changed')
];

// Server-side form validation
$form = EditProductForm::validate($fields);

$product = new UpdateProductDTO(
    $id,
    $fields['name'],
    $fields['description'],
    $fields['price'],
    $fields['condition'],
    $fields['conditionDetails'],
    $fields['discount'],
    $fields['category'],
    $fields['imageChanged'],
    $fields['displayImageFile']
);

$existingProduct = $products->findByName($product->name);
if ($existingProduct && $existingProduct->id !== $id) {
    $form->error('name', 'A product with this name already exists')->throw();
}

if (!$products->update($product)) {
    abort(500);
}

Session::toast("Your lsiting has been updated. See how it looks <a href='/products/{$id}'>here</a>");
$path = $auth->pm() ? '/' : '/seller/dashboard';
redirect($path);
