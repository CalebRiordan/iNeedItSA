<?php

namespace Http\Forms;

use InvalidArgumentException;

class CheckoutForm extends Form
{
    protected $errors = [];

    public function __construct(array $attributes)
    {
        $missing = self::missingKeys($attributes, ["address", "card_num", "csv_code"]);
        if (!empty($missing)) {
            throw new InvalidArgumentException("LoginForm requires a '{$missing[0]}' attribute to validate - none provided.");
        }

        $this->attributes = $attributes;

        if (!self::string($attributes['address'], 5, 100)) {
            $this->errors['address'] = "Please provide an address between 5 and 100 characters";
        }
        $this->notEmpty('address', "Address");

        if (!is_numeric($attributes['card_num']) || strlen($attributes['card_num']) !== 16) {
            $this->errors['card_num'] = "Card number must 16 characters - digits only";
        }
        $this->notEmpty('card_num', "Card number");

        if (!is_numeric($attributes['csv_code']) || strlen($attributes['csv_code']) !== 3) {
            $this->errors['csv_code'] = "CSV code must 3 characters - digits only";
        }
        $this->notEmpty('csv_code', "CSV code");
    }
}
