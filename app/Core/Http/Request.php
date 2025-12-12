<?php declare(strict_types=1);

namespace App\Core\Http;

class Request
{
    protected string $method;
    protected string $uri;
    protected array $headers;
    protected string $body;
    protected array $query;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        empty($_SERVER['QUERY_STRING']) ? $query = [] : parse_str($_SERVER['QUERY_STRING'], $query);
        $this->query = $query;
        $this->headers = $this->handlerHeaders();
        $this->body = file_get_contents('php://input');
    }

    protected function handlerHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $name = strtolower(str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5))))));
                $headers[$name] = $value;
            }
        }
        return $headers;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getQuery(): array
    {
        return $this->query;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeader(string $name): ?string
    {
        return $this->headers[strtolower($name)] ?? null;
    }

    public function getBody(): string
    {
        return $this->body;
    }

}