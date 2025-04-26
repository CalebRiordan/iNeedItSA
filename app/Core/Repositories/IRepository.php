<?php

namespace Core;

use BaseDTO;

interface IRepository
{
    public function find(string $id): ?BaseDTO;
    public function findAll(): BaseDTO;
    public function create(BaseDto $data): BaseDTO;
    public function update(string $id, BaseDTO $data): bool;
    public function delete(string $id): bool;
}