<?php

use Core\Repositories\UserRepository;
use Core\Session;
use Core\ValidationException;
use Http\Forms\Form;

$userId = Session::get('user')['id'];
$users = new UserRepository();

if ($users->isPendingSeller($userId)) {
    Session::toast("You have already registered to become a seller. Your application is being reviewed!", "info");
    redirect('/');
}

function validDocument($file)
{
    if (
        !$file
        || !isset($file['tmp_name'])
        || !is_uploaded_file($file['tmp_name'])
        || $file['error'] !== UPLOAD_ERR_OK
    ) {
        return false;
    }

    $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    return in_array($ext, $allowed, true);
}

$copyId = Form::getImageField("id_copy");
$proofOfAddress = Form::getImageField("poa");

if (!validDocument($copyId) || !validDocument($proofOfAddress)) {
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
if (!$users->saveSellerDocs($userId, $copyIdName, $poaName)) {
    Session::toast("An error occurred while trying to save the documents. Please try again later.", "error");
    redirect("/seller/register");
}

Session::toast("Seller registration sent. Your application is being reviewed!");
redirect("/");
