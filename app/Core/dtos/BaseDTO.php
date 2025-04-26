<?php

abstract class BaseDTO implements JsonSerializable
{

    protected array $sqlMapping;

    /**
     * Returns an associative array mapping table fields to DTO properties.
     *
     * @return array<string, string> Field-to-property mapping
     */
    abstract protected function setSqlMapping(): array;

    public function jsonSerialize(): array
    {
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties();

        $result = [];
        foreach ($properties as $property) {
            $result[$property->getName()] = $property->getValue($this);
        }

        return $result;
    }

    public static function fromArray(array $data): self
    {
        // Getting the names of the properties of a class using reflection in PHP
        // A form of 'metaprogramming'
        $reflection = new ReflectionClass(static::class);
        $instance = $reflection->newInstanceWithoutConstructor();

        foreach ($data as $key => $value) {
            if (isset($sqlMapping[$key])) {
                if ($reflection->hasProperty($key)){
                    $property = $reflection->getProperty($key);
                    $property->setValue($instance, $value);
                }
            }
        }

        return $instance;
    }
}
