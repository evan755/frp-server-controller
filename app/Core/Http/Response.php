<?php declare(strict_types=1);

namespace App\Core\Http;

class Response
{
    protected array $headers;
    protected string $body {
        set {
            $this->body = $value;
        }
    }
    protected int $code {
        set {
            $this->code = $value;
        }
    }

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