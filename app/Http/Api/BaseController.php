<?php

namespace Http\Api;

use Core\IRepository;

abstract class BaseController{

    protected object $repository;

    public function __construct() {
        $this->repository = $this->repo();
    }

    public function relativePath($path, $resource){
        return str_replace('/'.$resource, '', $path);
    }

    abstract protected function repo(): IRepository;

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