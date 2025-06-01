<?php 

namespace Http\Forms;

use InvalidArgumentException;

class RegistrationForm extends Form{
    protected $errors = [];

    public function __construct(array $attributes)
    {
        $required = [
            "first_name",
            "last_name",
            "email",
            "password",
            "phone_no",
            "location",
            "province",
            "address",
            "ship_address"
        ];

        $missing = self::missingKeys($attributes, $required);
        if (!empty($missing)){
            throw new InvalidArgumentException("RegistrationForm requires a '{$missing[0]}' attribute to validate - none provided.");
        }

        $this->attributes = $attributes;

        if (!self::lettersOnly($attributes['first_name'], 1, 50)) {
            $this->errors['first_name'] = "First name must be letters only and 50 characters or less";
        }
        $this->notEmpty('first_name', "First name");
        
        if (!self::lettersOnly($attributes['last_name'], 1, 50)) {
            $this->errors['last_name'] = "Last name must be letters only and 50 characters or less";
        }
        $this->notEmpty('last_name', "Last name");

        if (!self::email($attributes['email'])) {
            $this->errors['email'] = "Please provide a valid email address.";
        }
        $this->notEmpty('email', "Email");

        if (!self::string($attributes['password'], 7, 20)) {
            $this->errors['password'] = "Please provide a password between 7 and 20 characters";
        }
        $this->notEmpty('password', "Password");

        if (!self::numbersOnly($attributes['phone_no'], 7, 20)) {
            $this->errors['phone_no'] = "Please provide a valid phone number between 7 and 20 characters";
        }
        $this->notEmpty('phone_no', "Phone number");

        if (!self::string($attributes['location'], 1, 100)) {
            $this->errors['location'] = "Location cannot be more than 100 characters";
        }
        $this->notEmpty('location', "Location");

        $validProvinces = [
            "Eastern Cape",
            "Free State",
            "Gauteng",
            "KwaZulu-Natal",
            "Limpopo",
            "Mpumalanga",
            "Northern Cape",
            "North West",
            "Western Cape"
        ];

        if (!in_array($attributes['province'], $validProvinces, true)) {
            $this->errors['province'] = "Province must be one of the nine South African provinces.";
        }
        $this->notEmpty('province', "Province");

        if (!self::string($attributes['address'], 1, 255)) {
            $this->errors['address'] = "Address can not exceed 255 characters";
        }
        $this->notEmpty('address', "Address");

        if (!self::string($attributes['ship_address'], 1, 255)) {
            $this->errors['ship_address'] = "Shipping address is required and must be 255 characters or less";
        }
        $this->notEmpty('ship_address', "Shipping Address");

        if (validImage($attributes['profile_pic']) && !self::acceptableImage($attributes['profile_pic'])){
            $this->errors['profile_pic'] = "Image must be a JPG, JPEG, and PNG no greater in size than 4MB";
        }
    }
}