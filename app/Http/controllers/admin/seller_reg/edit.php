<?php

use Core\DTOs\Role;
use Core\Repositories\UserRepository;

$userId = $_POST['user_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!($userId && $action)) {
    redirect('/admin#sellers');
}

$users = new UserRepository();
if ($action === 'approve') {
    $users->approveSeller($userId);    
} elseif ($action === 'reject') {
    $users->approveSeller($userId, false);
}

redirect('/admin#sellers');