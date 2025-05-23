<?php 

namespace Http\Forms;

use InvalidArgumentException;

class LoginForm extends Form{
    protected $errors = [];

    public function __construct(array $attributes)
    {
        $missing = self::missingKeys($attributes, ["email", "password"]);
        if (!empty($missing)){
            throw new InvalidArgumentException("LoginForm requires a '{$missing[0]}' attribute to validate - none provided.");
        }

        $this->attributes = $attributes;

        if (!self::email($attributes['email'])) {
            $this->errors['email'] = "Please provide a valid email address.";
        }

        if ($attributes['password'] && !self::string($attributes['password'], 7, 20)) {
            $this->errors['password'] = "Please provide a password between 7 and 20 characters";
        }
    }
}