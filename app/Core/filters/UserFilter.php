<?php

namespace Core\Filters;

class UserFilter extends BaseFilter
{
    public function setProduct(string $productId): void
    {
        $this->criteria['product'] = $productId;
    }

    public function setName(string $userName): void
    {
        $this->criteria['name'] = $userName;
        $this->numBindings['name'] = 5;
    }

    public function sellersOnly()
    {
        $this->criteria['sellersOnly'] = True;
    }

    public function buyersOnly()
    {
        $this->criteria['buyersOnly'] = True;
    }

    public function getWhereClause(): string
    {
        $conditions = [];

        foreach ($this->criteria as $key => $value) {
            switch ($key) {
                case 'product':
                    $conditions[] = <<<SQL
                        user_id = (
                            SELECT user_id
                            FROM seller
                            WHERE user_id = (
                                SELECT seller_id
                                FROM Product
                                WHERE product_id = ?
                            )
                        )
                    SQL;
                    break;

                case 'name':
                    $conditions[] = <<<SQL
                        ({$this->sqlSearch('first_name')} 
                        OR {$this->sqlSearch('last_name')} 
                        OR CONCAT(first_name, \" \", last_name) LIKE CONCAT('%', ?, '%'))
                    SQL;
                    break;

                case 'sellersOnly':
                    $conditions[] = "is_seller = True";

                case 'buyersOnly':
                    $conditions[] = "is_buyer = True AND is_seller = False";

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
                    case 'product':
                        $this->setProduct($value);
                        break;
                    case 'name':
                        $this->setName($value);
                        break;
                    case 'sellersOnly':
                        $this->sellersOnly();
                        break;
                    case 'buyersOnly':
                        $this->buyersOnly($value);
                        break;
                }
            }
        }
    }
}
