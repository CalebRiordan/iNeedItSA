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

    public function sellersOnly(bool $includeBuyers = True)
    {
        $this->criteria['sellersOnly'] = $includeBuyers;
    }

    public function setCommunity(string $communityId)
    {
        $this->criteria['community'] = $communityId;
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
                    $condition = "is_seller = True";
                    $conditions[] = $value ? $condition : "{$condition} AND is_buyer = False";              

                case 'community':
                    $conditions[] = "user_id IN (SELECT user_id FROM user_participation WHERE comm_id = ?)";             

                default:
                    throw new \Exception("Invalid filter criteria '{$key}'");
            }
        }

        return $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
    }
}
