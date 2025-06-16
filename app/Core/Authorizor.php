<?php

namespace Core;

use Core\Container;
use Core\Database;
use Core\Session;

class Authorizor
{
    private Database $db;

    public function __construct()
    {
        $this->db = Container::resolve(Database::class);
    }

    public static function guest()
    {
        return !Session::has('user');
    }

    public static function auth()
    {
        return Session::has('user');
    }

    public static function seller()
    {
        // Anything a Seller can do, a Product manager can do
        return (Session::get('user')['sellerProfile'] ?? self::pm());
    }

    public static function staff()
    {
        return (Session::has('emp'));
    }

    public static function admin()
    {
        return static::empWithRole('admin');
    }

    public static function mod()
    {
        return static::empWithRole('mod') || static::empWithRole('admin');
    }

    public static function pm()
    {
        return static::empWithRole('pm') || static::empWithRole('admin');
    }

    private static function empWithRole($role)
    {
        return (Session::get('emp')['role'] ?? null) === $role;
    }

    public function ownsProduct(string $productId)
    {
        if (self::pm()){
            return true;
        }

        $sql = "SELECT 1 FROM product WHERE product_id = ? AND seller_id = ? LIMIT 1";
        $userId = Session::get('user')['id'] ?? null;
        $result = $userId ? $this->db->query($sql, [$productId, $userId])->find() : [];
        return !empty($result);
    }
}
