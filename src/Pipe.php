<?php

/*
 * PSR-15 Dispatcher by @ajgarlag
 *
 * Copyright (C) Antonio J. García Lagar <aj@garcialagar.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ajgarlag\Psr15\Dispatcher;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Pipe implements MiddlewareInterface
{
    /**
     * @var ServerMiddlewareInterface[]
     */
    private array $middlewares = [];

    private function __construct()
    {
    }

    /**
     * @param MiddlewareInterface[] $middlewares
     */
    public static function create(array $middlewares = []): self
    {
        $pipe = new self();
        foreach ($middlewares as $middleware) {
            $pipe = $pipe->withConnectedMiddleware($middleware);
        }

        return $pipe;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $requestHandler): ResponseInterface
    {
        if (empty($this->middlewares)) {
            return $requestHandler->handle($request);
        }

        $stack = Stack::create($requestHandler, array_reverse($this->middlewares));

        return $stack->handle($request);
    }

    public function withConnectedMiddleware(MiddlewareInterface $middleware): self
    {
        $pipe = clone $this;
        array_push($pipe->middlewares, $middleware);

        return $pipe;
    }
}
