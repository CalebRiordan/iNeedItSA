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
                if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                    // Middleware::resolve($route['middleware']);

                    return require base_path('app/Http/Controllers/' . $route['controller']);
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
