<?php declare(strict_types=1);

namespace App\Core;

use App\Core\Http\Router;

class Application
{
    protected object $router;

    public function bootstrap(): static
    {
        $this->router = Router::getInstance();
        require_once $this->router->getRouteFile();
        return $this;
    }

    public function run(): void
    {
        $this->router->match();
    }
}