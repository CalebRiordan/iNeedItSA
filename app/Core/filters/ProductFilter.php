<?php

class ProductFilter extends BaseFilter
{
    public function setSearch(string $searchQuery)
    {
        $this->criteria['search'] = $searchQuery;
        $this->numBindings['search'] = 4;
    }

    public function setCategory(int $index)
    {
        $categories = ['clothing', 'electronics', 'home_beauty', 'books_stationary', 'toys', 'beauty', 'sports', 'pets'];
        $this->criteria['category'] = $categories[$index];
    }

    public function setMinPrice(float $amount)
    {
        $this->criteria['minPrice'] = $amount;
    }

    public function setMaxPrice(float $amount)
    {
        $this->criteria['maxPrice'] = $amount;
    }

    public function setBrand(string $brand)
    {
        $this->criteria['brand'] = $brand;
    }

    public function rating(int $minRating)
    {
        $this->criteria['rating'] = $minRating;
    }

    public function setPage(int $page, int $perPage)
    {
        $this->limit = $perPage;
        $this->offset = ($page - 1) * $perPage;
    }

    public function getWhereClause(): string
    {
        $conditions = [];

        foreach ($this->criteria as $key => $value) {
            switch ($key) {
                case 'search':
                    $conditions[] = "({$this->sqlSearch('name')} OR {$this->sqlSearch('desc')})";
                    break;
                case 'category':
                    $conditions[] = "category = ?";
                    break;
                case 'minPrice':
                    $conditions[] = "price >= ?";
                    break;
                case 'maxPrice':
                    $conditions[] = "price <= ?";
                    break;
                case 'brand':
                    $conditions[] = "brand = ?";
                    break;
                case 'rating':
                    $conditions[] = "avg_rating >= ?";
                    break;

                default:
                    throw new \Exception("Invalid filter criteria '{$key}'");
            }
        }

        return $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
    }
}
