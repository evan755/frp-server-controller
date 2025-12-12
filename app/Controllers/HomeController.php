<?php declare(strict_types=1);

namespace App\Controller;

use App\Core\Http\Request;
use App\Core\Http\Response;

class HomeController
{
    public function index(Request $request, Response $response): void
    {
        $response->setHeader('Content-Type', 'application/json');
        $response->setbody(json_encode(['message' => 'Frp Server Controller is working!']));
        $response->send();
    }
}