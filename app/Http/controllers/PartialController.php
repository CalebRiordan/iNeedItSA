<?php

namespace Http\Controllers;

use Core\Filters\ProductFilter;
use Core\Repositories\OrderRepository;
use Core\Repositories\ProductRepository;

class PartialController
{
    public static function handle($partial, $params)
    {
        switch ($partial) {
            case 'products-display':
            case 'page-selector':
                response(static::renderProductDisplay($params));
                break;
            case 'cart':
                echo json_encode(static::renderCart($params));
                break;
            case 'order':
                echo json_encode(static::renderOrderItemList($params));
                break;
            default:
                response(['error' => 'Partial not found.'], 404);
        }
        exit;
    }

    public static function renderProductDisplay($params): ?array
    {
        $validParams = ProductFilter::validParams($params);
        if (!$validParams) return [];
        $filter = new ProductFilter();
        $filter->build($validParams);

        $repository = new ProductRepository();

        // products-display variables
        $products = $repository->findAllPreviews($filter);

        // page-selector variables
        $productCount = $repository->getCount($filter);
        $currentPage = $validParams['page'] ?? 1;

        return [
            "products-display" => self::renderPartial("products-display", ['products' => $products]),
            "page-selector" => self::renderPartial("page-selector", [
                'productCount' => $productCount,
                'currentPage' => $currentPage
            ]),
        ];
    }

    public static function renderCart()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return abort(404);

        // Read request body as a stream and json decode it
        $input = json_decode(file_get_contents('php://input'), true);


        if (!isset($input['items']) || !is_array($input['items'])) {
            response(['error' => "Invalid input. No 'items' key found for cart items."], 400);
        }

        $ids = array_column($input['items'], 'product_id');
        if (empty($ids)) {
            response([]);
        }

        $filter = new ProductFilter();
        $filter->setIds($ids);
        $products = (new ProductRepository())->findAllPreviews($filter);

        // Set quantity for each product
        // Create a map of product_id => quantity for quick lookup
        $quantities = [];

        foreach ($input['items'] as $item) {
            $quantities[$item['product_id']] = $item['quantity'];
        }

        foreach ($products as $product) {
            if (isset($quantities[$product->id])) {
                $product->quantity = $quantities[$product->id];
            }
        }

        return self::renderPartial("cart", ['products' => $products]);
    }

    public static function renderOrderItemList($params): string
    {
        $id = $params["id"] ?? null;

        if (!$id) {
            response(['error' => "Invalid input. No 'id' key found for order."], 400);
        }

        try {
            $items = (new OrderRepository())->findItemsById($id);
        } catch (\Throwable $th) {
            response(["error" => "Error retrieving order"], 500);
        }

        return self::renderPartial("order-items-list", ["items" => $items]);
    }

    private static function renderPartial($path, $data = [])
    {
        extract($data);

        // Return HTML as text using output buffering (ob)
        ob_start();
        require partial("{$path}");
        return ob_get_clean();
    }
}
