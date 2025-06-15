<?php

namespace Core;

use Core\Middleware;

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
            'middleware' => [],
            'middleware_deny' => false
        ];

        $this->nextType = 'page';

        return $this;
    }

    public function partial($uri, string $method = "GET")
    {
        $this->nextType = 'partial';
        return $this->add($method, $uri, 'PartialController.php');
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

    public function only(...$keys)
    {
        // Flatten if the first argument is an array
        if (count($keys) === 1 && is_array($keys[0])) {
            $keys = $keys[0];
        }

        $this->routes[array_key_last($this->routes)]['middleware'] = $keys;
        return $this;
    }

    public function deny(...$keys)
    {
        // Flatten if the first argument is an array
        if (count($keys) === 1 && is_array($keys[0])) {
            $keys = $keys[0];
        }

        $this->routes[array_key_last($this->routes)]['middleware'] = $keys;
        $this->routes[array_key_last($this->routes)]['middleware_deny'] = True;

        return $this;
    }

    public function route($uri, $method)
    {
        $isPartial = str_starts_with($uri, '/partial');
        $uri = $isPartial ? substr($uri, 8) : $uri;

        $isPartial ? $this->routePartial($uri, $method) : $this->routePage($uri, $method);
    }

    public function routePage($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['type'] === 'page') {
                $params = static::routeMatch($route, $uri, $method);
                if ($params) {
                    Middleware::resolve($route['middleware'], $route['middleware_deny']);

                    return $this->controller($route['controller'], $params);
                }
            }
        }
        abort();
    }

    public function routePartial($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['type'] === 'partial') {
                $params = static::routeMatch($route, $uri, $method);
                if ($params) {
                    Middleware::resolve($route['middleware'], $route['middleware_deny']);

                    // Require PartialController.php and call method on class
                    require_once base_path('app/http/controllers/' . $route['controller']);

                    $controllerName = str_replace('.php', '', $route['controller']);
                    $partialController = 'Http\\Controllers\\' . $controllerName;
                    $partial = trim(preg_replace('/\{[^}]+\}/', '', $route['uri']), '/');
                    $params = array_merge($params, $_GET);

                    return $partialController::handle($partial, $params);
                }
            }
        }
        abort();
    }

    private static function routeMatch(array $route, string $uri, string $method)
    {
        $pattern = '#^' . preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route["uri"]) . '$#';
        if (preg_match($pattern, $uri, $matches) && $route['method'] === strtoupper($method)) {
            return $matches;
        }
        return [];
    }

    private function controller($controller, $params)
    {
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

    public function getUri()
    {
        return parse_url($_SERVER["REQUEST_URI"])['path'];
    }

    public function getMethod()
    {
        return $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
    }
}
