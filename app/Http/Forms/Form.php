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

    protected static function missingKeys(array $attributes, array $keys): array{
        $missing = [];
        foreach ($keys as $key){
            if (!isset($attributes[$key])){
                $missing[] = $key;
            }
        }
        
        return $missing;
    }
}
