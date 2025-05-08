<?php

namespace Core\Filters;

abstract class BaseFilter
{
    protected array $criteria = [];
    protected array $numBindings = [];
    protected ?int $limit = null;
    protected ?int $offset = null;
    protected ?string $orderBy = null;
    
    abstract public function getWhereClause(): string;
    
    abstract public function build(array $params);
    
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

    public function setLimit(int $number){
        $this->limit = $number;
    }

    public function setOffset(int $number){
        $this->offset = $number;
    }

    public function orderBy($field, $sortOrder = 'ASC'){
        $sortOrder = strtoupper($sortOrder);
        if (!in_array($sortOrder, ['ASC', 'DESC'])){
            throw new \InvalidArgumentException("Sort order must be 'ASC' or 'DESC'; '{$sortOrder} provided.'");
        }

        $this->orderBy = "{$field} {$sortOrder}";
    }

    public function reset()
    {
        $this->criteria = [];
        $this->numBindings = [];
        $this->limit = null;
        $this->offset = null;
        $this->orderBy = null;
    }

    public function getLimitClause(?int $default = null): string {
        $this->limit = $this->limit ?? $default;

        return $this->limit === null ? '' : "LIMIT {$this->limit}";
    }

    public function getOffsetClause(?int $default = null): string {
        $this->offset = $this->offset ?? $default;

        return $this->offset === null ? '' : "OFFSET {$this->offset}";
    }

    public function getOrderByClause(?string $defaultField = null): string{
        $this->orderBy = $this->orderBy ?? $defaultField;
        return $this->orderBy === null ? '' : "ORDER BY {$this->orderBy}";
    }
}
