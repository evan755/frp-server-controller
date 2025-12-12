<?php declare(strict_types=1);

namespace App\Core\Http;

class Response
{
    protected array $headers;
    protected string $body;
    protected int $code;

    public function __construct()
    {
        $this->headers = ['Content-Type' => 'text/html; charset=utf-8'];
        $this->body = '';
        $this->code = 200;
    }

    public function setHeader(string $key, string $value): void
    {
        $this->headers = [$key => $value];
    }

    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function send()
    {
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }
        http_response_code($this->code);
        echo $this->body;
        exit;
    }
}