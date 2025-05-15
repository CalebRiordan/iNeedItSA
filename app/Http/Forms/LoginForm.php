<?php 

namespace Http\Forms;

use Core\ValidationException;
use Core\Validator;
use Exception;
use InvalidArgumentException;

class LoginForm extends Form{
    protected $errors = [];

    public function __construct(array $attributes)
    {
        $missing = self::missingKeys($attributes, ["email, password"]);
        if (!empty($missing)){
            throw new InvalidArgumentException("LoginForm requires a '{$missing[0]}' attribute to validate - none provided.");
        }

        $this->attributes = $attributes;

        if (!LoginForm::email($attributes['email'])) {
            $this->errors['email'] = "Please provide a valid email address.";
        }

        if ($attributes['password'] && !LoginForm::string($attributes['password'], 7, 20)) {
            $this->errors['password'] = "Please provide a password between 7 and 20 characters";
        }
    }
    
    private static function email($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    private static function string($value, $min = 1, $max = INF)
    {
        $value = trim($value);

        return strlen($value) >= $min && strlen($value) <= $max;
    }
}