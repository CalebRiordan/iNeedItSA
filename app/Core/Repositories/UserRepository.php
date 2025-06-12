<?php

namespace Core\Repositories;

use Core\DTOs\BaseDTO;
use Core\DTOs\BuyerProfileDTO;
use Core\DTOs\CreateUserDTO;
use Core\DTOs\LoginDTO;
use Core\DTOs\PendingSellerDTO;
use Core\DTOs\Role;
use Core\DTOs\UpdateUserDTO;
use Core\DTOs\UserDTO;
use Core\DTOs\UserPreviewDTO;
use Core\Filters\UserFilter;
use Exception;

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
        // Check if user exists
        if ($this->findByEmail($user->email)) return null;

        // Verify password
        $user->password = password_hash($user->password, PASSWORD_BCRYPT);

        $user->setProfilePicUrl($user->profilePicFile ?
            $this->saveProfilePicture($user->profilePicFile) :
            null);

        $fields = $user->toFields();
        $placeholders = $user->placeholders();

        // Insert new User record
        $sql = <<<SQL
            INSERT INTO user 
            ({$fields}, date_joined)
            VALUES ({$placeholders}, ?)
        SQL;

        $newId = $this->db->query($sql, ...$user->getMappedValues())->newId();

        // Insert corresponding Buyer record
        $buyerRole = new BuyerProfileDTO($user->shipAddress, 0);
        $this->addRole($newId, Role::Buyer->value, ['ship_address' => $user->shipAddress]);

        // Return new UserDTO
        $userValues[] = $buyerRole;
        return new UserDTO(
            $newId,
            ...$user->getMappedValues(),
        );
    }

    private function saveProfilePicture(array $file): ?string
    {
        if (validImage($file)) {
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('pfp_', true) . '.' . $extension;

            $targetPath = "/uploads/profile_pics/{$filename}";

            if (move_uploaded_file($file['tmp_name'], base_path('public/' . $targetPath))) {
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
        $id = $user->id;
        $existingUser = $this->findById($id);

        if (!$existingUser) {
            return false;
        }

        if ($user->imageChanged) {
            $user->setProfilePicUrl($user->profilePicFile ?
                $this->saveProfilePicture($user->profilePicFile) :
                'delete');
        }

        // Prepare UPDATE query
        $sets = $user->getMappedUpdateSet();

        $sql = <<<SQL
            UPDATE user 
            SET {$sets}
            WHERE user_id = ?
        SQL;

        // Update user
        if (!($this->db->query($sql, [$id])->wasSuccessful())) return false;

        // Update shipping address in Buyer table
        if ($this->exists($id) && $user->shipAddress) {
            return $this->db->query(
                "UPDATE buyer SET ship_address = ? WHERE user_id = ?",
                [$user->shipAddress, $id]
            )->wasSuccessful();
        }

        return false;
    }

    public function addRole(string $id, string $role, array $mapping)
    {
        $fields = implode(', ', array_keys($mapping));
        $values = array_values($mapping);
        $placeholders = BaseDTO::placeholders(count($mapping));
        
        // Add role in in corresponding role table
        $this->db->query("INSERT INTO {$role} ({$fields}) VALUES ({$placeholders})", $values);

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
        $result = $this->db->query("SELECT 1 FROM user WHERE user_id = ? LIMIT 1", [$id])->find();

        return !empty($result);
    }

    public function saveSellerDocs(string $userId, string $copyIdFilename, string $poaFilename): bool
    {
        return $this->db->query(
            "INSERT INTO seller_docs_url (user_id, copy_id_url, poa_url, date_submitted) VALUES (?, ?, ?, ?)",
            [$userId, $copyIdFilename, $poaFilename, date('Y-m-d')],
        )->wasSuccessful();
    }

    public function isPendingSeller(string $userId): bool
    {
        $result = $this->db->query("SELECT user_id from seller_docs_url WHERE user_id = ?", [$userId])->find();
        return !empty($result);
    }

    public function new(string $period)
    {
        $daysMap = [
            'week' => 7,
            'month' => 30,
            'year' => 365,
        ];

        if (!isset($daysMap[$period])) {
            throw new Exception("'{$period}' is not a recognized period for filtering. Must be week, month, or year.");
        }

        $days = $daysMap[$period];

        $sql = <<<SQL
            SELECT date_joined FROM user  
            WHERE date_joined > (NOW() - INTERVAL {$days} DAY)
        SQL;

        return $this->db->query($sql)->findAll();
    }

    public function pendingSellers(): array
    {
        $fields = PendingSellerDTO::toFields('u');
        $sql = <<<SQL
        SELECT {$fields}, sdu.copy_id_url, sdu.poa_url, sdu.date_submitted FROM user u
        INNER JOIN seller_docs_url sdu ON u.user_id = sdu.user_id
        WHERE sdu.accepted = FALSE
        SQL;

        $rows = $this->db->query($sql)->findAll();

        return PendingSellerDTO::fromRows($rows);
    }

    public function deleteSellerReg(string $userId)
    {
        $this->db->query(
            "DELETE FROM seller_docs_url WHERE user_id = ?",
            [$userId]
        )->wasSuccessful();
    }
}
