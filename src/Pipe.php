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

class Pipe implements MiddlewareInterface
{
    /**
     * @var ServerMiddlewareInterface[]
     */
    private $middlewares = [];

    private function __construct()
    {
    }

    /**
     * @param MiddlewareInterface[] $middlewares FIFO array of middlewares
     *
     * @return self
     */
    public static function create(array $middlewares = []): self
    {
        $pipe = new self();
        foreach ($middlewares as $middleware) {
            $pipe = $pipe->withConnectedMiddleware($middleware);
        }

        return $pipe;
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $requestHandler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $requestHandler): ResponseInterface
    {
        if (empty($this->middlewares)) {
            return $requestHandler->handle($request);
        }

        $stack = Stack::create($requestHandler, array_reverse($this->middlewares));

        return $stack->handle($request);
    }

    /**
     * Creates a new pipe with the given middleware connected.
     *
     * @param MiddlewareInterface $middleware
     *
     * @return self
     */
    public function withConnectedMiddleware(MiddlewareInterface $middleware): self
    {
        $pipe = clone $this;
        array_push($pipe->middlewares, $middleware);

        return $pipe;
    }
}
