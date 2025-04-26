<?php

namespace Http\Api;

use Core\IRepository;
use Core\OrderRepository;

class OrderController extends BaseController
{
    public function repo(): IRepository{
        return new OrderRepository();
    }

    public function handle(string $path, string $httpMethod, array $params)
    {
    }

    public function get($id) {}

    public function getAll($params) {}

    public function create() {
        // store order in database
        // update products of order
        // return view? return code? return value for router to handle? 
    }

    public function update($id) {}

    public function delete($id) {}
}
