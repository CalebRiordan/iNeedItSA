<?php

abstract class BaseFilter
{
    protected array $criteria = [];
    protected array $numBindings = [];
    protected ?int $limit = null;
    protected ?int $offset = null;
    
    abstract public function getWhereClause(): string;
    
    /**
     * Get an array of values to be passed as parameters that satisfy the 
     * placeholders in a query constructed using some BaseFilter.
     * @return string[]
     */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->criteria as $key => $value) {
            $repeat = $this->numBindings[$key] ?? 1;
            $values = array_merge($values, array_fill(0, $repeat, $value));
        }
        return $values;
    }

    public function sqlSearch($fieldName)
    {
        return "{$fieldName} LIKE CONCAT('%', ?, '%') OR SOUNDEX({$fieldName}) = SOUNDEX(?)";
    }

    public function clear()
    {
        $this->criteria = [];
        $this->numBindings = [];
    }

    public function getLimitClause(): string {
        if ($this->limit === null) return '';
        return "LIMIT {$this->limit} OFFSET {$this->offset}";
    }
}
