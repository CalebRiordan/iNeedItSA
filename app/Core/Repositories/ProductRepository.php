<?php

namespace Core;

class ProductRepository extends BaseRepository implements IRepository
{

    public function find(string $id, $preview=True): ?ProductDTO
    {
        $result = $this->db->query("SELECT * FROM Product WHERE product_id = ?", [$id])->find();

        return ProductDTO::fromArray($result);
    }

    public function findAll($criteria): array
    {
        return $this->db->query("SELECT * FROM Product")->find();
    }

    public function findAllPreviews($criteria): array
    {
        return $this->db->query("SELECT product_id, name, price, <img>, discount, rating, category FROM Product WHERE <criteria>")->find();
    }

    public function create(array $data): array {}

    public function update(string $id, array $data): bool {}

    public function delete(string $id): bool {}
}
