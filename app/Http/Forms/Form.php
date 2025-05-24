<?php

namespace Http\Forms;

use Core\ValidationException;

class Form
{
    protected $errors = [];
    protected $attributes = [];

    public static function validate($attributes)
    {
        $instance = new static($attributes);

        return $instance->failed() ? $instance->throw() : $instance;
    }

    public function throw()
    {
        ValidationException::throw($this->errors, $this->attributes);
    }

    public function failed()
    {
        return (bool) count($this->errors);
    }

    public function errors()
    {
        return $this->errors;
    }

    public function error($field, $message)
    {
        $this->errors[$field] = $message;

        return $this;
    }

    protected static function missingKeys(array $attributes, array $keys): array
    {
        $missing = [];
        foreach ($keys as $key) {
            if (!isset($attributes[$key]) || $attributes[$key] === null || $attributes[$key] === "") {
                $missing[] = $key;
            }
        }

        return $missing;
    }

    protected function notEmpty($inputName, $realName)
    {
        if (!isset($this->attributes[$inputName])) {
            throw new \InvalidArgumentException("No attribute with name '{$inputName}'");
        }

        $value = $this->attributes[$inputName];
        if ($value === '' || (is_array($value) && empty($value))) {
            $this->errors[$inputName] = "{$realName} is required.";
        }
    }

    protected static function email($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    protected static function string($value, $min = 1, $max = INF)
    {
        $value = trim($value);

        return strlen($value) >= $min && strlen($value) <= $max;
    }

    protected static function lettersOnly($value, $min = 1, $max = INF)
    {
        $value = trim($value);

        return preg_match('/^[a-zA-Z]+$/', $value) && strlen($value) >= $min && strlen($value) <= $max;
    }

    protected static function numbersOnly($value, $min = 1, $max = 20)
    {
        $value = str_replace(" ", "", $value);

        return is_numeric($value) && strlen($value) >= $min && strlen($value) <= $max;
    }

    protected static function acceptableImage(array $image)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileName = $image['name'];
        $fileSize = $image['size'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions) || $fileSize > 2 * 1024 * 1024) { // Max 4MB
            return false;
        }

        return true;
    }
}
