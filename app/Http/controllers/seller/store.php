<?php

use Core\Repositories\UserRepository;
use Core\Session;
use Core\ValidationException;

$userId = Session::get('user')['id'];
$users = new UserRepository();

if ($users->pendingSeller($userId)){
    Session::toast("You have already registered to become a seller. Your application is being reviewed!");
    redirect('/');
}

$copyId = noImageUploaded($_FILES["id_copy"]) ? null : $_FILES["id_copy"];
$proofOfAddress = noImageUploaded($_FILES["poa"]) ? null : $_FILES["poa"];

if (!validImage($copyId) || !validImage($proofOfAddress)) {
    // Reload form - include more rigourous checks in the future
    ValidationException::throw(['files' => 'Both documents are required. Accepted formats are PDF, JPG, PNG'], []);
}

function uploadImage($file, $prefix)
{
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid($prefix, true) . '.' . $extension;

    $path = "storage/seller_docs/{$filename}";

    if (!move_uploaded_file($file['tmp_name'], base_path($path))) {
        Session::toast("An error occurred while trying to save the documents. Please try again later.", "error");
        redirect("/seller/register");
    }

    return $filename;
}

// Store images on file system
$copyIdName = uploadImage($copyId, 'id_');
$poaName = uploadImage($proofOfAddress, 'poa_');

// Upload URLs to database
if (!$users->saveSellerDocs($userId, $copyIdName, $poaName)){
    Session::toast("An error occurred while trying to save the documents. Please try again later.", "error");
    redirect("/seller/register");
}

Session::toast("Seller registration sent. Your application is being reviewed!");
redirect("/");