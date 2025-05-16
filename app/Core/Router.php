<?php

namespace Core;

class Router
{
    protected $routes = [];
    protected $nextType = 'page';

    public function add($method, $uri, $controller)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
            'type' => $this->nextType,
        ];

        $this->nextType = 'page';

        return $this;
    }

    public function partial($uri)
    {
        $this->nextType = 'partial';
        return $this->add('GET', $uri, 'PartialController.php');
    }

    public function get($uri, $controller)
    {
        return $this->add('GET', $uri, $controller);
    }

    public function post($uri, $controller)
    {
        return $this->add('POST', $uri, $controller);
    }

    public function delete($uri, $controller)
    {
        return $this->add('DELETE', $uri, $controller);
    }

    public function patch($uri, $controller)
    {
        return $this->add('PATCH', $uri, $controller);
    }

    public function put($uri, $controller)
    {
        return $this->add('PUT', $uri, $controller);
    }

    public function only($key)
    {
        $this->routes[array_key_last($this->routes)]['middleware'] = $key;

        return $this;
    }

    public function route($uri, $method)
    {
        $isPartial = str_starts_with($uri, '/partial');
        $uri = $isPartial ? substr($uri, 8) : $uri;

        $isPartial ? $this->routePartial($uri, $method) : $this->routePage($uri, $method);
    }

    public function routePartial($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['type'] === 'partial') {
                if (str_starts_with($uri, $route['uri']) && $route['method'] === strtoupper($method)) {
                    require_once base_path('app/http/controllers/' . $route['controller']);
                    
                    $controllerName = str_replace('.php', '', $route['controller']);
                    $partialController = 'Http\\Controllers\\' . $controllerName;
                    
                    $partial = ltrim(strstr($uri, '?', true) ?: $uri, '/');

                    $partialController::handle($partial, $_GET);
                    return;
                }
            }
        }
        $this->abort();
    }

    public function routePage($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['type'] === 'page') {
                $params = Router::routeMatch($route, $uri, $method);
                if ($params) {
                    // Middleware::resolve($route['middleware']);

                    return $this->controller($route['controller'], $params);
                }
            }
        }
        $this->abort();
    }

    private static function routeMatch(array $route, string $uri, string $method){
        $pattern = '#^' . preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route["uri"]) . '$#';
        if (preg_match($pattern, $uri, $matches) && $route['method'] === strtoupper($method)){
            return $matches;
        }
        return [];
    }

    private function controller($controller, $params){
        try {
            return require base_path('app/Http/Controllers/' . $controller);
        } catch (\Core\ValidationException $ex) {
            Session::flash('errors', $ex->errors);
            Session::flash('old', $ex->old);
            
            static::redirectToPrevious();
        }
    }

    public static function redirectToPrevious()
    {
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function getUri(){
        return parse_url($_SERVER["REQUEST_URI"])['path'];
    }

    public function abort($code = 404)
    {
        http_response_code($code);

        require base_path("views/StatusCodes/{$code}.php");

        die();
    }
}
