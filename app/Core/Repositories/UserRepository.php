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
use Core\Filters\UserFilter;

class UserRepository extends BaseRepository
{

    public function findById(string $id): ?UserDTO
    {
        $sql = <<<SQL
            SELECT u.*, b.*, s.*,
            (SELECT COUNT(*) FROM product p WHERE p.seller_id = u.user_id) AS total_ads,
            (SELECT SUM(views) FROM product p WHERE p.seller_id = u.user_id) AS total_views
            FROM user u
            LEFT JOIN buyer b ON u.user_id = b.user_id
            LEFT JOIN seller s ON u.user_id = s.user_id
            WHERE u.user_id = ?
            SQL;

        $row = $this->db->query($sql, [$id])->find();
        if (!$row) {
            return null;
        }

        // Workaround for overlapping 'user_id' fields
        $row['user_id'] = $id;
        $user = UserDTO::fromRow($row);

        return $user;
    }

    public function findPreviewById(string $id): ?UserPreviewDTO
    {
        $fields = UserPreviewDTO::toFields();
        $sql = "SELECT {$fields} FROM user WHERE u.user_id = ?";

        $row = $this->db->query($sql, [$id])->find();

        return UserPreviewDTO::fromRow($row);
    }

    public function findByEmail($email): ?LoginDTO
    {
        $fields = LoginDTO::toFields();
        $sql = "SELECT {$fields} FROM user WHERE email = ?";

        return LoginDTO::fromRow(
            $this->db->query($sql, [$email])->find()
        );
    }

    public function findByToken($token): ?UserDTO
    {
        $row = $this->db->query(
            "SELECT user_id FROM user WHERE login_token = ?",
            [$token]
        )->find();

        return $row ? $this->findById($row["user_id"]) : null;
    }

    public function findAllPreviews(?UserFilter $filter = null): array
    {
        $filter ??= new UserFilter();
        $fields = UserPreviewDTO::toFields();
        $where = $filter->getWhereClause();

        $sql = "SELECT {$fields} FROM user {$where}";
        $rows = $this->db->query($sql, $filter->getValues())->find();

        $users = UserDTO::fromRows($rows);
        return $users;
    }

    public function create(CreateUserDTO $user): ?UserDTO
    {
        if ($this->findByEmail($user->email)) return null;

        $user->password = password_hash($user->password, PASSWORD_BCRYPT);

        $user->profilePicUrl = !empty($user->profilePicFile) ?
            $this->saveProfilePicture($user->profilePicFile) :
            null;

        $fields = CreateUserDTO::toFields();
        $placeholders = CreateUserDTO::placeholders();

        // Insert new User record
        $sql = <<<SQL
            INSERT INTO user 
            ({$fields}, date_joined)
            VALUES ({$placeholders}, ?)
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

    private function saveProfilePicture(array $file): ?string
    {
        if (validImage($file)) {
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('pfp_', true) . '.' . $extension;

            $targetPath = "/uploads/profile_pics/{$filename}";

            if (move_uploaded_file($file['tmp_name'], base_path('public/'.$targetPath))) {
                return $targetPath;
            }
        }

        return null;
    }

    public function saveToken(string $email, string $token)
    {
        $hashedToken = password_hash($token, PASSWORD_BCRYPT);

        $sql = <<<SQL
            UPDATE user
            SET login_token = ?
            WHERE email = ?
        SQL;

        $success = $this->db->query($sql, [$hashedToken, $email])->wasSuccessful();

        if (!$success) {
            throw new \Exception("Failed to save user login token to database for user with email '{$email}'");
        }
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
        $sets = $user->getMappedUpdateSet();
        $isBuyer = $user->buyerProfile ? true : false;
        $isSeller = $user->sellerProfile ? true : false;

        $sql = <<<SQL
            UPDATE user 
            SET {$sets}, is_buyer = {$isBuyer}, is_seller = {$isSeller}
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
        $this->handleRoleAdjustment($id, "buyer", $new, $existing);
    }

    public function updateSellerRole(string $id, ?SellerProfileDTO $new, ?SellerProfileDTO $existing = null)
    {
        $this->handleRoleAdjustment($id, "seller", $new, $existing);
    }

    private function handleRoleAdjustment(string $id, string $role, ?SellerProfileDTO $new, ?SellerProfileDTO $existing = null)
    {
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

        $fields = $dtoClass::toFields();
        $placeholders = $dtoClass::placeholders();

        // Add role in in corresponding role table
        $this->db->query(
            "INSERT INTO {$role} ({$fields}) VALUES ({$placeholders})",
            [$id, ...$profile->getMappedValues()]
        );

        // Set subtype discriminator in User table
        $this->db->query(
            "UPDATE user SET is_{$role} = 1 WHERE user_id = ?",
            [$id]
        );
    }

    public function updateRole(string $id, string $role, BaseDTO $profile)
    {
        $placeholders = $profile->getMappedUpdateSet();
        $this->db->query(
            "UPDATE {$role} SET ({$placeholders}) WHERE user_id = ?",
            [$id]
        );
    }

    public function removeRole(string $id, string $role)
    {
        $this->db->query("DELETE FROM {$role} WHERE user_id = ?", [$id]);

        $this->db->query("UPDATE user SET is_{$role} = 0 WHERE user_id = ?", [$id]);
    }

    public function delete(string $id): bool
    {
        if ($this->exists($id)) {
            return false;
        }

        return $this->db->query("DELETE * FROM user WHERE user_id = ?", [$id])->wasSuccessful();
    }

    public function exists(string $id): bool
    {
        $result = $this->db->query("SELECT 1 FROM user WHERE user_id = ? LIMIT 1")->find();

        return !empty($result);
    }
}
