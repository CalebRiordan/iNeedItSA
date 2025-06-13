<?php

namespace Core\Filters;

class ProductFilter extends BaseFilter
{
    public function setIds(array $ids)
    {
        if (!empty($ids)) {
            $this->criteria['ids'] = $ids;
            $this->numBindings['ids'] = count($ids);
        }
    }

    public function setSearch(string $searchQuery)
    {
        $this->criteria['search'] = $searchQuery;
        $this->numBindings['search'] = 4;
    }

    public function setCategory(int $index)
    {
        $categories = ['Clothing', 'Electronics', 'Home & Garden', 'Books & Stationary', 'Toys', 'Beauty', 'Sports', 'Pets'];
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

    public function rating(int $minRating, string $prefix = "")
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

    public function seller(string $userId)
    {
        $this->criteria['seller'] = $userId;
    }

    public function getWhereClause(string $prefix = ''): string
    {
        $prefix .= $prefix ? '.' : '';
        $conditions = [];

        foreach ($this->criteria as $key => $value) {
            switch ($key) {
                case 'ids':
                    $placeholders = implode(', ', array_fill(0, $this->numBindings['ids'], '?'));
                    $conditions[] = "{$prefix('ids')}product_id IN ({$placeholders})";
                    break;
                case 'search':
                    $conditions[] = "({$this->sqlSearch('name',$prefix)} OR {$this->sqlSearch('description',$prefix)})";
                    break;
                case 'category':
                    $conditions[] = "{$prefix}category = ?";
                    break;
                case 'minPrice':
                    $conditions[] = "{$prefix}price * (1 - COALESCE({$prefix}pct_discount, 0) / 100) >= ?";
                    break;
                case 'maxPrice':
                    $conditions[] = "{$prefix}price * (1 - COALESCE({$prefix}pct_discount, 0) / 100) <= ?";
                    break;
                case 'rating':
                    $conditions[] = "{$prefix}avg_rating >= ?";
                    break;
                case 'discount':
                    $conditions[] = "{$prefix}pct_discount > 0";
                    break;
                case 'seller':
                    $conditions[] = "{$prefix}seller_id = ?";
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
                    case 'ids':
                        $this->setSearch($value);
                        break;
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
                    case 'rating':
                        $this->rating($value);
                        break;
                    case 'page':
                        $this->setPage($value, 16);
                        break;
                    case 'seller':
                        $this->seller($value);
                        break;
                }
            }
        }
    }

    public static function validParams($params): ?array
    {
        $validParams = [];

        $val = $params['search'] ?? "";
        $val = htmlspecialchars($val, ENT_QUOTES);
        if (!empty($val)) {
            $validParams['search'] = $val;
        }

        $val = $params['category'] ?? null;
        if ($val !== null && ctype_digit((string)$val) && (int)$val >= 0 && (int)$val <= 7) {
            $validParams['category'] = (int)$val;
        }

        $val = $params['minPrice'] ?? null;
        if ($val && is_numeric($val) && (float)$val >= 0) {
            $validParams['minPrice'] = (float)$val;
        }

        $val = $params['maxPrice'] ?? null;
        if ($val && is_numeric($val) && (float)$val >= 0) {
            $validParams['maxPrice'] = (float)$val;
        }

        $val = $params['rating'] ?? null;
        if ($val && ctype_digit((string)$val) && (int)$val >= 1 && (int)$val <= 5) {
            $validParams['rating'] = (int)$val;
        }

        $val = $params['page'] ?? 1;
        $validParams['page'] = (ctype_digit((string)$val) && (int)$val >= 1) ? (int)$val : 1;

        return $validParams;
    }
}
