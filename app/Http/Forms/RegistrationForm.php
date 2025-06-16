<?php 

namespace Http\Forms;

use InvalidArgumentException;

class RegistrationForm extends Form{
    protected $errors = [];

    public function __construct(array $attributes)
    {
        $required = [
            "firstName",
            "lastName",
            "email",
            "password",
            "phoneNo",
            "location",
            "province",
            "address",
            "shipAddress"
        ];

        $missing = self::missingKeys($attributes, $required);
        if (!empty($missing)){
            throw new InvalidArgumentException("RegistrationForm requires a '{$missing[0]}' attribute to validate - none provided.");
        }

        $this->attributes = $attributes;

        if (!self::lettersOnly($attributes['firstName'], 1, 50)) {
            $this->errors['firstName'] = "First name must be letters only and 50 characters or less";
        }
        $this->notEmpty('firstName', "First name");
        
        if (!self::lettersOnly($attributes['lastName'], 1, 50)) {
            $this->errors['lastName'] = "Last name must be letters only and 50 characters or less";
        }
        $this->notEmpty('lastName', "Last name");

        if (!self::email($attributes['email'])) {
            $this->errors['email'] = "Please provide a valid email address.";
        }
        $this->notEmpty('email', "Email");

        if (!self::string($attributes['password'], 7, 20)) {
            $this->errors['password'] = "Please provide a password between 7 and 20 characters";
        }
        $this->notEmpty('password', "Password");

        if (!self::numbersOnly($attributes['phoneNo'], 7, 20)) {
            $this->errors['phoneNo'] = "Please provide a valid phone number between 7 and 20 characters";
        }
        $this->notEmpty('phoneNo', "Phone number");

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

        if (!self::string($attributes['shipAddress'], 1, 255)) {
            $this->errors['shipAddress'] = "Shipping address is required and must be 255 characters or less";
        }
        $this->notEmpty('shipAddress', "Shipping Address");

        if (validImage($attributes['profilePic']) && !self::acceptableImage($attributes['profilePic'])){
            $this->errors['profilePic'] = "Image must be a JPG, JPEG, or PNG no larger than 4MB";
        }
    }
}