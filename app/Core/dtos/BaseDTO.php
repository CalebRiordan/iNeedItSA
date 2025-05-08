<?php

namespace Core\DTOs;

use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;

abstract class BaseDTO implements JsonSerializable
{
    /**
     * Associative array mapping table fields to DTO properties | [string => string]
     */
    protected static array $sqlMapping = [];

    protected static array $excludeFromReflection = ['sqlMapping'];

    public static function getSqlMapping(): array
    {
        return static::$sqlMapping;
    }

    public static function getExclusions()
    {
        return array_merge(self::$excludeFromReflection, static::$excludeFromReflection ?? []);
    }

    public static function toFields(?string $prefix = null): string
    {
        $mapping = static::getSqlMapping();
        if (empty($mapping))
            return '';

        $fields = [];
        foreach ($mapping as $field => $_) {
            $fields[] = $prefix !== null ? "{$prefix}.{$field}" : $field;
        }

        return implode(', ', $fields);
    }

    public static function placeholders(): string
    {
        $mapping = static::getSqlMapping();
        if (empty($mapping))
            return '';

        return implode(', ', array_fill(0, count($mapping), '?'));
    }

    public static function fromRow(array $row): ?static
    {
        if (!$row)
            return null;

        // Getting the names of the properties of a class using reflection in PHP
        // A form of 'metaprogramming'
        $reflection = new ReflectionClass(static::class);
        $instance = $reflection->newInstanceWithoutConstructor();
        $mapping = static::getSqlMapping();
        $exclusions = static::getExclusions();

        foreach ($row as $field => $value) {
            $propName = $mapping[$field] ?? $field;

            // Will automatically exclude mappings with value of ""
            if ($reflection->hasProperty($propName) && !in_array($propName, $exclusions)) {
                $property = $reflection->getProperty($propName);
                $property->setValue($instance, $value);
            }
        }

        return $instance;
    }
    /** @return self[] */
    public static function fromRows(array $rows): array
    {
        $array = [];
        foreach ($rows as $row) {
            $array[] = static::fromRow($row);
        }

        return $array;
    }

    /**
     * Constructs and returns a string that represents the assignments in the WHERE clause of 
     * an UPDATE query for the DTO for all mapped properties. Mapped properties are those 
     * that have corresponding database field names.
     * 
     * @return string
     */
    public function getMappedUpdateSet(): string
    {
        $mappedFields = static::mappedFields();
        $mappedValues = $this->getMappedValues();

        if (count($mappedFields) != count($mappedValues)) {
            throw new \LogicException("Mapped fields/values mismatch");
        }

        $updateSetString = "";
        for ($i = 0; $i < count($mappedFields); $i++) {
            $updateSetString = "{$mappedFields[$i]} = {$mappedValues[$i]}, ";
        }

        return substr($updateSetString, 0, -2);
    }

    /**
     * Get an array of values of all properties of the class
     * 
     * @param array $exclude 
     * Optional array of names of properties to exclude in the result
     * @return string[]
     */
    public function getValues(array $exclude = []): array
    {
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties();
        $exclude = array_merge($exclude, static::getExclusions());

        $values = [];
        $propertyNames = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            
            if (!in_array($propertyName, $exclude, true)) {
                $propertyNames[] = $propertyName;
                $values[] = $property->getValue($this);
            }
        }

        $invalidExclusions = array_diff($exclude, $propertyNames);
        if (!empty($invalidExclusions)) {
            throw new \InvalidArgumentException("Property '{$invalidExclusions[0]}' does not exist in the class.");
        }

        return $values;
    }

    /**
     * Get an array of values of only mapped properties of the class. Mapped properties are those 
     * that have corresponding database field names
     * 
     * @return mixed[]
     */
    public function getMappedValues(): array
    {
        $reflection = new ReflectionClass($this);

        $values = [];
        $mapping = static::getSqlMapping();
        foreach ($mapping as $fieldName => $propertyName) {
            if ($propertyName !== '') {
                if ($reflection->hasProperty($propertyName)) {
                    $values[] = $reflection->getProperty($propertyName)->getValue();
                }
            }
        }

        return $values;
    }

    private static function mappedFields(): array
    {
        $mapping = static::getSqlMapping();
        if (empty($mapping))
            return [];

        return array_keys(array_filter(
            $mapping,
            fn($property) => $property !== ''
        ));
    }

    public function jsonSerialize(): array
    {
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $exclusions = static::getExclusions();

        $result = [];
        foreach ($properties as $property) {
            $propName = $property->getName();
            if (!in_array($propName, $exclusions)){
                $result[$propName] = $property->getValue($this);
            }
        }

        return $result;
    }
}
