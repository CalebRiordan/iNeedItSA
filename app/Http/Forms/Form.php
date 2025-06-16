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
        // ValidationExceptions are explicitly caught in the Router
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
            if (!array_key_exists($key, $attributes)) {
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

    protected static function numbersOnly($value, $min = 1, $max = 20, $minValue = null, $maxValue = null)
    {
        $value = str_replace(" ", "", $value);

        $isNumeric = is_numeric($value);
        $lengthValid = strlen($value) >= $min && strlen($value) <= $max;
        $minValid = $minValue !== null ? $value >= $minValue : true;
        $maxValid = $maxValue !== null ? $value <= $maxValue : true;

        return $isNumeric && $lengthValid && $minValid && $maxValid;
    }

    protected static function acceptableImage(array $image)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $fileName = $image['name'];
        $fileSize = $image['size'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions) || $fileSize > 2 * 1024 * 1024) { // Max 4MB
            return false;
        }

        return true;
    }

    public static function getField($name)
    {
        return htmlspecialchars($_POST[$name] ?? '', ENT_QUOTES);
    }

    public static function getNumericField($name): int|bool
    {
        $val = self::getField($name);
        return is_numeric($val) ? (int)$val : false;
    }

    public static function getImageField($name)
    {
        $file = $_FILES[$name];
        return $file['error'] === UPLOAD_ERR_NO_FILE ? null : $file;
    }

    public static function getBoolField($name)
    {
        return self::getField($name) === 'true' ? true : false;
    }
}
