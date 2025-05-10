<?php

namespace Http\Api;

use Core\BaseRepository;

abstract class BaseController
{

    protected $repository;

    public function relativePath($path, $resource)
    {
        return str_replace('/' . $resource, '', $path);
    }

    protected function safeJsonResponse(callable $callback)
    {
        try {
            $data = $callback();
            if (empty($data)) {
                returnJson();
            } else {
                returnJson($data);
            }
        } catch (\Throwable $e) {
            returnJson(["error" => "An error occurred."], 500);
        }
    }

    protected function pathToRegex($path)
    {
        /*  Converts a path string into a regex that can be used to match dynamic paths with route parameters
            e.g. /products/{id}  ->  #^/products/(?P<id>[^/]+)$# */

        $findRouteParam = '#\{(\w+)\}#';
        $routeParamRegex = '(?P<$1>[^/]+)';

        $regex = preg_replace($findRouteParam, $routeParamRegex, $path);
        return "#^" . $regex . "$#";
    }

    abstract public function handle(string $relativePath, string $httpMethod, array $params);
}
