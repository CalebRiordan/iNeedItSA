<?php

namespace Core\Filters;

class ProductFilter extends BaseFilter
{
    public function setSearch(string $searchQuery)
    {
        $this->criteria['search'] = $searchQuery;
        $this->numBindings['search'] = 4;
    }

    public function setCategory(int $index)
    {
        $categories = ['clothing', 'electronics', 'home_garden', 'books_stationary', 'toys', 'beauty', 'sports', 'pets'];
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

    public function rating(int $minRating)
    {
        $this->criteria['rating'] = $minRating;
    }

    public function hasDiscount()
    {
        $this->criteria['discount'] = True;
        $this->numBindings['discount'] = 0;
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
                    $conditions[] = "({$this->sqlSearch('name')} OR {$this->sqlSearch('description')})";
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
                case 'rating':
                    $conditions[] = "avg_rating >= ?";
                    break;
                case 'discount':
                    $conditions[] = "pct_discount > 0";
                    break;
                default:
                    throw new \Exception("Invalid filter criteria '{$key}'");
            }
        }

        return $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
    }


    public function build(array $params)
    {
        if ($params) {
            foreach ($params as $key => $value) {
                switch ($key) {
                    case 'search':
                        $this->setSearch($value);
                        break;
                    case 'category':
                        $this->setCategory($value);
                        break;
                    case 'minPrice':
                        $this->setMinPrice($value);
                        break;
                    case 'maxPrice':
                        $this->setMaxPrice($value);
                        break;
                    case 'minRating':
                        $this->rating($value);
                        break;
                    case 'page':
                        $this->setPage($value, 16);
                        break;
                }
            }
        }
    }
}
