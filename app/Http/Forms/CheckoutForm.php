<?php

namespace Http\Forms;

use InvalidArgumentException;

class CheckoutForm extends Form
{
    protected $errors = [];

    public function __construct(array $attributes)
    {
        $missing = self::missingKeys($attributes, ["address", "card_num"]);
        if (!empty($missing)) {
            throw new InvalidArgumentException("LoginForm requires a '{$missing[0]}' attribute to validate - none provided.");
        }

        $this->attributes = $attributes;

        if (!is_numeric($attributes['card_num']) || strlen($attributes['card_num']) !== 16) {
            $this->errors['card_num'] = "Card number must 16 characters - digits only";
        }
        $this->notEmpty('card_num', "Card number");

        if (!self::string($attributes['address'], 5, 100)) {
            $this->errors['address'] = "Please provide an address between 5 and 100 characters";
        }
        $this->notEmpty('address', "Address");
    }
}
