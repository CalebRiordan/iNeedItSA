<?php

namespace Http\Api;

use Core\Repositories\ProductRepository;

class ProductController extends BaseController
{

    /**
     * @var ProductsRepository
     */
    protected $repository;

    public function __construct(){
        $this->repository = new ProductRepository();
    }

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

    public function get($id) {
        $product = $this->repository->findById($id);
        if ($product){
            returnJson($product);
        }

        abort(404);
    }

    public function getAll($params) {}

    public function create() {}

    public function update($id) {}

    public function delete($id) {}
}
