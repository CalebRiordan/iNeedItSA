<?php

use Core\DTOs\Role;
use Core\Repositories\UserRepository;

$userId = $_POST['user_id'] ?? null;
$action = $_POST['action'] ?? null;

if ($userId && $action) {
    $userRepo = new UserRepository();

    try {
        if ($action === 'approve') {
            $userRepo->addRole($userId, Role::Seller->value, ['date_registered' => date('Y-m-d')]);
        } elseif ($action === 'reject') {
            $userRepo->deleteSellerReg($userId);
        }
    } catch (\Throwable $th) {
        $message = $th->getMessage();
        echo "<h1>{$message}<h1>";
        exit;
    }
}

redirect('/admin#sellers');
