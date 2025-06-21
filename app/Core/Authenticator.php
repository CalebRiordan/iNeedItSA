<?php

namespace Core;

use Core\DTOs\UserDTO;
use Core\Repositories\CartRepository;
use Core\Repositories\UserRepository;
use Core\Session;
use Core\Container;
use Core\Database;
use Core\DTOs\SellerProfileDTO;

class Authenticator
{
    protected $users;
    protected $userSessionTimeout = 7200;

    public function __construct()
    {
        $this->users = new UserRepository();
    }

    public function attempt($email, $password)
    {
        $user = $this->users->findByEmail($email);


        if ($user && password_verify($password, $user->password)) {
            // LoginDTO -> UserDTO
            $user = $this->users->findById($user->id);
            static::login($user);

            return true;
        }

        return false;
    }

    public static function login(UserDTO $user)
    {
        // New user session
        Session::put('user', [
            'id' => $user->id,
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'email' => $user->email,
            'location' => $user->location,
            'profilePicUrl' => $user->profilePicUrl,
            'buyerProfile' => $user->buyerProfile,
            'sellerProfile' => $user->sellerProfile
        ]);

        // Track activity
        Session::put("last_activity", time());

        // Store new token to prevent CSRF 
        if (!Session::has('csrf_token')) {
            Session::put('csrf_token', bin2hex(random_bytes(32)));
        }

        // Retrieve cart from database
        $cart = (new CartRepository())->findByUser($user->id);
        Session::put('cart', $cart);

        // Sync cart after login
        Session::flash('sync_cart', true);

        session_regenerate_id(true);
    }

    public static function logout()
    {
        // Save cart state to database
        if (Session::has('cart') && !(new CartRepository())->persist(
            Session::get('user')['id'],
            Session::get('cart')
        )) {
            Session::toast("Error occurred while trying to save cart. Unable to log out", "error");
        }


        // Clear user session data 
        Session::remove('user');
        Session::remove('cart');

        // Destroy session if compeltely empty
        if (Session::empty()) {
            Session::clear();
            static::expireCookie('PHPSESSID');
        }
    }

    private static function expireCookie($name)
    {
        $params = session_get_cookie_params();
        setcookie($name, '', time() - 3600, $params['path'], $params['domain']);
    }

    public function setPersistentLoginCookie($email)
    {
        $token = bin2hex(random_bytes(32));
        setcookie("remember_login", $token, time() + (60 * 60 * 24 * 60), "/", "", false, true); // 2 months

        try {
            $this->users->saveToken($email, $token);
        } catch (\Exception) {
            static::expireCookie('remember_me');
        }
    }

    public function updateLoginState()
    {
        if (Session::has('user')) {

            // 1. User session exists but is expired
            if (!isset($_COOKIE['remember_login']) && (time() - $_SESSION['last_activity']) > $this->userSessionTimeout) {
                $this->logout();
                return False;
            }

            Session::put("last_activity", time());
            Session::flash('sync_cart', true);
            return True;
        }

        // 2. No user session exists but persistent login is enabled
        if (isset($_COOKIE['remember_login'])) {
            $token = $_COOKIE['remember_login'];
            $user = $this->users->findByToken($token);

            if ($user) {
                $this->login($user);
                return True;
            } else {
                static::expireCookie('remember_login');
                return false;
            }
        }

        return false;
    }

    public function checkSellerState()
    {
        // Get user in session
        $user = Session::get('user');
        $id = $user['id'];

        // Do not continue if user is already a seller
        if ($user['sellerProfile'] !== null) return;

        // Fetch seller registration data
        $db = Container::resolve(Database::class);
        $seller = $db->query("SELECT approved, rejected, has_seen_response FROM seller_reg_docs WHERE user_id = ?", [$id])->find();

        // Do not continue if user has not registered as seller or has already logged in since response
        if (!empty($seller) && $seller['has_seen_response']) return;

        if ($seller['approved']) {
            // Seller is approved
            // Send Success toast message
            Session::toast(
                "Congratulations! You have been approved as a seller. You can access your Seller Dashboard from the options menu, 
                just click your profile icon!",
                "success",
                8000
            );

            // Add seller profile to user's session
            $sellerData = $db->query("SELECT * FROM seller WHERE user_id = ?", [$id])->find();
            $user['sellerProfile'] = SellerProfileDTO::fromRow($sellerData);
            Session::put('user', $user);
        } elseif ($seller['rejected']) {
            // Seller is rejected: Send Info toast message
            Session::toast(
                "Unfortunately your registration to become a seller has been declined. Please review your documents before resubmitting. 
                If you have queries, please contact ineeditsa@support.com",
                "info",
                8000
            );
        } else return;

        // Set has_seen_response to true
        $db->query("UPDATE seller_reg_docs SET has_seen_response=1 WHERE user_id = ?", [$id]);
    }
}
