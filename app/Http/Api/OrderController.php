<?php

namespace Http\Api;

class ProductController extends BaseController
{
    public function handle(string $path, string $httpMethod, array $params)
    {
        $relativePath = $this->relativePath($path, 'products');
        $id = str_replace('/', '', $relativePath);

        switch ($httpMethod) {
            case 'GET':
                return $id ? $this->get($id) : $this->getAll($params);
            case 'POST':
                return $this->create();
            case 'PUT':
                return $this->update($id);
            case 'DELETE':
                return $this->delete($id);
        }
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
