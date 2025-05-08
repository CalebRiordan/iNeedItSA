<?php

namespace Http\Api;

use Core\Repositories\ProductRepository;
use Core\Filters\ProductFilter;

class ProductController extends BaseController
{

    /**
     * @var ProductsRepository
     */
    protected $repository;

    public function __construct()
    {
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
            default:
                abort(400);
        }
    }

    public function get($id)
    {
        $this->safeJsonResponse(function () use ($id) {
            return $this->repository->findById($id);
        });
    }

    public function getAll($params)
    {
        $this->safeJsonResponse(function () use ($params) {

            $filter = new ProductFilter();
            $filter->build($params);

            return $this->repository->findAllPreviews($filter);
        });
    }

    public function create() {}

    public function update($id) {}

    public function delete($id) {}
}
