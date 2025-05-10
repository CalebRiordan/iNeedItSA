<?php

namespace Http\Controllers;

use Core\Filters\ProductFilter;
use Core\Repositories\ProductRepository;

class PartialController
{
    public function handle($partial, $params)
    {
        $partial = $_GET['partial'] ?? '';
        switch ($partial) {
            case 'products-display':
                $this->renderProductGrid($params);
                break;
            default:
                abort(404);
        }
    }

    public static function renderProductGrid($params): array
    {
        $validParams = filterProductsParams($params);
        if (!$validParams) return [];

        $filter = new ProductFilter();
        $filter->build($params);

        $repository = new ProductRepository();

        // products-display variables
        $products = $repository->findAllPreviews($filter);

        // page-selector variables
        $productCount = $repository->getCount($filter);
        $currentPage = $validParams['page'] ?? 1;

        return [
            "products-display" => self::renderPartial("views/partials/products-display.php", ['products' => $products]),
            "page-selector" => self::renderPartial("views/partials/page-selector.php", [
                'productCount' => $productCount,
                'currentPage' => $currentPage
            ]),
        ];
    }

    private static function renderPartial($path, $data = [])
    {
        extract($data);

        // Return HTML as text using output buffering (ob)
        ob_start();
        require base_path($path);
        return ob_get_clean();
    }
}
