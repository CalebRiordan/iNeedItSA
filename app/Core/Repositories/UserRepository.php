<?php

namespace Core\Repositories;

use Core\DTOs\BaseDTO;
use Core\DTOs\BuyerProfileDTO;
use Core\DTOs\CreateUserDTO;
use Core\DTOs\LoginDTO;
use Core\DTOs\Role;
use Core\DTOs\SellerProfileDTO;
use Core\DTOs\UpdateUserDTO;
use Core\DTOs\UserDTO;
use Core\DTOs\UserPreviewDTO;
use UserFilter;

class UserRepository extends BaseRepository
{

    public function findById(string $id): ?UserDTO
    {
        $sql = <<<SQL
            SELECT u.*, b.*, s.*,
            (SELECT COUNT(*) FROM products p WHERE p.seller_id = u.user_id) AS total_ads,
            (SELECT SUM(views) FROM products p WHERE p.seller_id = u.user_id) AS total_views
            FROM User u
            LEFT JOIN Buyer b ON u.user_id = b.user_id
            LEFT JOIN Seller s ON u.user_id = s.user_id
            WHERE u.user_id = ?
            SQL;

        $row = $this->db->query($sql, [$id])->find();

        if (!$row) {
            return null;
        }

        $user = UserDTO::fromRow($row);

        return $user;
    }

    public function findPreviewById(string $id): ?UserPreviewDTO
    {
        $sql = "SELECT {ProductPreviewDTO::toFields()} FROM User WHERE u.user_id = ?";

        $row = $this->db->query($sql, [$id])->find();

        return UserPreviewDTO::fromRow($row);
    }

    public function findByEmail($email): ?LoginDTO
    {
        $sql = "SELECT {LoginDTO::toFields()} FROM User WHERE email = ?";

        return LoginDTO::fromRow(
            $this->db->query($sql, [$email])->find()
        );
    }

    public function findAllPreviews(?UserFilter $filter): array
    {
        $sql = "SELECT {UserPreviewDTO::toFields()} FROM User {$filter->getWhereClause()}";
        $rows = $this->db->query($sql, $filter->getValues())->find();
        $users = UserDTO::fromRows($rows);
        return $users;
    }

    public function create(CreateUserDTO $user): ?UserDTO
    {
        if ($this->findByEmail($user->email))
            return null;

        $user->password = password_hash($user->password, PASSWORD_BCRYPT);

        // Insert new User record
        $sql = <<<SQL
            INSERT INTO User 
            ({CreateUserDTO::toFields()}, date_joined )
            VALUES ({CreateUserDTO::placeholders()}, ?)
        SQL;
        $userValues = [
            ...$user->getMappedValues(),
            date("Y-m-d")
        ];

        $newId = $this->db->query($sql, $userValues)->newId();

        // Insert corresponding Buyer record
        $buyerRole = new BuyerProfileDTO($user->shipAddress, 0);
        $this->addRole($newId, Role::Buyer->value, $buyerRole);

        // Return new UserDTO
        $userValues[] = $buyerRole;
        return new UserDTO(
            $newId,
            ...$userValues,
        );
    }

    public function update(UpdateUserDTO $user): bool
    {
        // Check existing user
        $existingUser = $this->findById($user->id);

        $newEmailTaken = $existingUser->email !== $user->email && $this->findByEmail($user->email);
        if (!$existingUser || $newEmailTaken) {
            return false;
        }

        // Prepare UPDATE query
        $isBuyer = $user->buyerProfile ? true : false;
        $isSeller = $user->sellerProfile ? true : false;

        $sql = <<<SQL
            UPDATE User 
            SET {$user->getMappedUpdateSet()}, is_buyer = {$isBuyer}, is_seller = {$isSeller}
            WHERE user_id = ?
        SQL;

        // Update user
        if (!$this->db->query($sql, $user->id)->wasSuccessful()) return false;

        // Update roles
        try {
            $this->updateBuyerRole($user->id, $user->buyerProfile, $existingUser->buyerProfile);
            $this->updateSellerRole($user->id, $user->sellerProfile, $existingUser->sellerProfile);
        } catch (\Throwable $th) {
            throw new \Exception("Failed to update user roles");
        }

        return true;
    }

    public function updateBuyerRole(string $id, ?BuyerProfileDTO $new, ?BuyerProfileDTO $existing = null)
    {
        $this->handleRoleAdjustment($id, "Buyer", $new, $existing);
    }

    public function updateSellerRole(string $id, ?SellerProfileDTO $new, ?SellerProfileDTO $existing = null)
    {
        $this->handleRoleAdjustment($id, "Seller", $new, $existing);
    }

    private function handleRoleAdjustment(string $id, string $role, ?SellerProfileDTO $new, ?SellerProfileDTO $existing = null){
        if ($new && !$existing) { // Add new role
            $this->addRole($id, $role, $new);
        } elseif ($new && $existing) { // Update existing role
            $this->updateRole($id, $role, $new);
        } elseif (!$new && $existing) { // Remove role
            $this->removeRole($id, $role);
        }
    }

    public function addRole(string $id, string $role, BaseDTO $profile)
    {
        $dtoClass = UserDTO::$roleClasses[$role];

        $this->db->query(
            "INSERT INTO {$role} ({$dtoClass::toFields()}) VALUES ({$dtoClass::placeholders()})", 
            [$id, ...$profile->getMappedValues()]
        );
    }

    public function updateRole(string $id, string $role, BaseDTO $profile)
    {
        $this->db->query(
            "UPDATE {$role} SET ({$profile->getMappedUpdateSet()}) WHERE user_id = ?",
            [$id]
        );
    }

    public function removeRole(string $id, string $role)
    {
        $this->db->query("DELETE FROM {$role} WHERE user_id = ?", [$id]);
    }

    public function delete(string $id): bool
    {
        if ($this->exists($id)) {
            return false;
        }

        return $this->db->query("DELETE * FROM User WHERE user_id = ?", [$id])->wasSuccessful();
    }

    public function exists(string $id): bool
    {
        $result = $this->db->query("SELECT 1 FROM User WHERE user_id = ? LIMIT 1")->find();

        return !empty($result);
    }
}
