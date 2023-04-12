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

final class Stack implements RequestHandlerInterface
{
    private RequestHandlerInterface $requestHandler;

    /**
     * @var MiddlewareInterface[]
     */
    private array $middlewares = [];

    private function __construct(RequestHandlerInterface $requestHandler)
    {
        $this->requestHandler = $requestHandler;
    }

    /**
     * @param MiddlewareInterface[] $middlewares
     */
    public static function create(RequestHandlerInterface $requestHandler, array $middlewares = []): self
    {
        $stack = new self($requestHandler);
        foreach ($middlewares as $middleware) {
            $stack = $stack->withPushedMiddleware($middleware);
        }

        return $stack;
    }

    public function handle(ServerRequestInterface $serverRequest): ResponseInterface
    {
        if (!($middleware = $this->peek()) instanceof \Psr\Http\Server\MiddlewareInterface) {
            return $this->requestHandler->handle($serverRequest);
        }

        return $middleware->process($serverRequest, $this->pop());
    }

    public function withPushedMiddleware(MiddlewareInterface $middleware): self
    {
        $stack = clone $this;
        array_unshift($stack->middlewares, $middleware);

        return $stack;
    }

    private function peek(): ?MiddlewareInterface
    {
        return reset($this->middlewares) ?: null;
    }

    private function pop(): self
    {
        $stack = clone $this;
        array_shift($stack->middlewares);

        return $stack;
    }
}
