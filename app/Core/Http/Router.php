<?php declare(strict_types=1);

namespace App\Core\Http;

class Router
{
    protected static object $routers;
    protected static ?object $_instance = null;
    protected string $file;

    protected function __construct()
    {
        $this->file = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'routes.php';
        file_exists($this->file) or touch($this->file);
        static::$routers = (object)[];
    }

    public static function getInstance(): object
    {
        if (!(static::$_instance instanceof self)) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    public function match(): void
    {
        $params = parse_url($_SERVER['REQUEST_URI']);
        $params['method'] = strtoupper($_SERVER['REQUEST_METHOD']);
        static::handler($params);
    }

    protected function handler(array $request): void
    {
        foreach (static::$routers as $name => $route) {
            if ($route['method'] === $request['method']) {
                preg_match_all('/\{([^}]+)\}/', $route['path'], $param);
                $regexPattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route['path']);
                if (preg_match("#^$regexPattern$#", $request['path'], $matches)) {
                    $params = array_combine($param[1], array_slice($matches, 1));
                    static::call($route['target'], $params);
                }
            }
        }
    }

    protected static function call(string $target, array $params = []): void
    {
        list($controller, $action) = explode('@', $target);
        $class = new ("App\\Controller\\" . $controller)();
        $class->$action(new Request(), new Response(), $params);
    }

    public function get(string $name, array $route): void
    {
        static::$routers->{$name} = ['method' => 'GET', 'path' => $route[0], 'target' => $route[1]];
    }

    public function post(string $name, array $route): void
    {
        static::$routers->{$name} = ['method' => 'POST', 'path' => $route[0], 'target' => $route[1]];
    }

    public function put(string $name, array $route): void
    {
        static::$routers->{$name} = ['method' => 'PUT', 'path' => $route[0], 'target' => $route[1]];
    }

    public function patch(string $name, array $route): void
    {
        static::$routers->{$name} = ['method' => 'PATCH', 'path' => $route[0], 'target' => $route[1]];
    }

    public function delete(string $name, array $route): void
    {
        static::$routers->{$name} = ['method' => 'DELETE', 'path' => $route[0], 'target' => $route[1]];
    }

    public function options(string $name, array $route): void
    {
        static::$routers->{$name} = ['method' => 'OPTIONS', 'path' => $route[0], 'target' => $route[1]];
    }

    public function getRouteFile(): string
    {
        return $this->file;
    }

    public function getRouters(): object
    {
        return static::$routers;
    }
}