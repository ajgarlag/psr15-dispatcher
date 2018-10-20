<?php

namespace tests\Ajgarlag\Psr15\Dispatcher;

use Http\Factory\Diactoros\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Middleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = new ResponseFactory();
        return $response->createResponse(200);
    }
}
