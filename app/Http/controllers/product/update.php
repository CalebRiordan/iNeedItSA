<?php

use Core\DTOs\UpdateProductDTO;
use Core\Repositories\ProductRepository;
use Core\Session;
use Http\Forms\EditProductForm;

$id = $params['id'];
$products = new ProductRepository();
$userId = Session::get('user')['id'];

// Product doesn't belong to this seller -> 404 Not Found (obscurity)
if (!$products->isFromSeller($id, $userId)) abort();

$fields = [
    "name" => htmlspecialchars($_POST["name"], ENT_QUOTES),
    "description" => htmlspecialchars($_POST["description"], ENT_QUOTES),
    "price" => htmlspecialchars($_POST["price"], ENT_QUOTES),
    "stock" => htmlspecialchars($_POST["stock"], ENT_QUOTES),
    "condition" => htmlspecialchars($_POST["condition"], ENT_QUOTES),
    "conditionDetails" => htmlspecialchars($_POST["condition_details"], ENT_QUOTES),
    "discount" => htmlspecialchars($_POST["discount"], ENT_QUOTES),
    "category" => htmlspecialchars($_POST["category"], ENT_QUOTES),
    "displayImageFile" => noImageUploaded($_FILES["product_img"]) ? null : $_FILES["product_img"],
    "imageChanged" => htmlspecialchars($_POST["image_changed"], ENT_QUOTES) === 'true' ? true : false,
];

// Server-side form validation
$form = EditProductForm::validate($fields);

$product = new UpdateProductDTO(
    $id,
    $fields["name"],
    $fields["description"],
    $fields["price"],
    $fields["condition"],
    $fields["conditionDetails"],
    $fields["discount"],
    $fields["category"],
    $fields["imageChanged"],
    $fields["displayImageFile"]
);

$existingProduct = $products->findByName($product->name);
if ($existingProduct && $existingProduct->id !== $id){
    $form->error('name', 'A product with this name already exists')->throw();
}

if (!$products->update($product)) {
    abort(500);
}

Session::toast("Your lsiting has been updated. See how it looks <a href='/products/{$id}'>here</a>");
redirect('/seller/dashboard');
