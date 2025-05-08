<?php

namespace Core;

class Router
{
    protected $routes = [];
    protected $apiNext = false;

    public function add($method, $uri, $controller)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
            'isApi' => $this->apiNext,
        ];

        $this->apiNext = false;

        return $this;
    }

    public function api()
    {
        $this->apiNext = true;
        return $this;
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
        $isApi = str_starts_with($uri, '/api');
        $uri = $isApi ? substr($uri, 4) : $uri;

        $isApi ? $this->routeApi($uri, $method) : $this->routePage($uri, $method);
    }

    public function routeApi($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['isApi']) {
                if (str_starts_with($uri, $route['uri']) && $route['method'] === strtoupper($method)) {

                    require_once base_path('app/http/api/' . $route['controller']);

                    $controllerName = str_replace('.php', '', $route['controller']);
                    $class = 'Http\\Api\\' . $controllerName;
                    $controller = new $class();

                    $controller->handle($uri, $route['method'], $_GET);
                    return;
                }
            }
        }
        $this->abort();
    }

    public function routePage($uri, $method)
    {
        foreach ($this->routes as $route) {
            if (!$route['isApi']) {
                if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                    // Middleware::resolve($route['middleware']);

                    return require base_path('app/http/controllers/' . $route['controller']);
                }
            }
        }
        $this->abort();
    }

    protected function parseApiUrl(string $url): array
    {
        $parts = parse_url($url);
        dd($parts);
        $path = $parts['path'] ?? '';

        $resourcePath = preg_replace('#^/api/#', '', $path);
        $segments = explode('/', $resourcePath);

        $queryParams = [];
        parse_str($parts['query'] ?? '', $queryParams);

        return [
            'resource' => $segments[0] ?? null,
            'relativePath' => count($segments) > 2
                ? '/' . implode('/', array_slice($segments, 2))
                : null,
            'queryParams' => $queryParams,
        ];
    }

    public function redirectToPrevious()
    {
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function abort($code = 404)
    {
        http_response_code($code);

        require base_path("views/StatusCodes/{$code}.php");

        die();
    }
}
