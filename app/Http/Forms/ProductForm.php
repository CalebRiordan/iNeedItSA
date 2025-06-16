<?php

namespace Http\Forms;

use InvalidArgumentException;

class ProductForm extends Form
{
    protected $errors = [];

    public function __construct(array $attributes)
    {
        $required = [
            "name",
            "description",
            "displayImageFile",
            "price",
            "category",
            "stock",
            "condition",
        ];

        $missing = self::missingKeys($attributes, $required);
        if (!empty($missing)) {
            throw new InvalidArgumentException("RegistrationForm requires a '{$missing[0]}' attribute to validate - none provided.");
        }

        $this->attributes = $attributes;

        if (!self::string($attributes['name'], 30, 255)) {
            $this->errors['name'] = "Product name must be at least 30 characters and less than 255";
        }
        $this->notEmpty('name', "Product name");

        if (!self::string($attributes['description'], 50, 1000)) {
            $this->errors['description'] = "Description can be between 50 and 1000 characters";
        }
        $this->notEmpty('description', "Description");

        if (validImage($attributes['displayImageFile']) && !self::acceptableImage($attributes['displayImageFile'])) {
            $this->errors['displayImageFile'] = "Image must be a JPG, JPEG, or PNG no larger than 4MB";
        }
        $this->notEmpty('displayImageFile', "A product image");

        if (!self::numbersOnly($attributes['price'], 2, 5, 20, 99999)) {
            $this->errors['price'] = "Price must be between R20 and R99 999";
        }
        $this->notEmpty('price', "Price");

        if (!self::numbersOnly($attributes['category'], 1, 1, 0, 8)) {
            $this->errors['category'] = "Category must be one of the provided options.";
        }
        $this->notEmpty('category', "Category");

        if (!self::numbersOnly($attributes['stock'], 1, 3, 1, 999)) {
            $this->errors['stock'] = "There must be at least one item in stock and no more than 999";
        }
        $this->notEmpty('stock', "Stock");

        $validConditions = ['New', 'Used', 'Refurbished'];
        if (!in_array($attributes['condition'], $validConditions, true)) {
            $this->errors['condition'] = "Condition must be either New, Used, or Refurbished";
        }
        $this->notEmpty('condition', "Condition");

        if (isset($attributes['conditionDetails']) && !self::string($attributes['conditionDetails'], 0, 100)) {
            $this->errors['conditionDetails'] = "Condition details cannot exceed 100 characters";
        }
    }
}
