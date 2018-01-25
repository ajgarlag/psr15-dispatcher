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

class Stack implements RequestHandlerInterface
{
    /**
     * @var RequestHandlerInterface
     */
    private $requestHandler;

    /**
     * @var MiddlewareInterface[]
     */
    private $middlewares = [];

    /**
     * @param RequestHandlerInterface $requestHandler
     */
    private function __construct(RequestHandlerInterface $requestHandler)
    {
        $this->requestHandler = $requestHandler;
    }

    /**
     * @param RequestHandlerInterface $requestHandler
     * @param MiddlewareInterface[]   $middlewares    LIFO array of middlewares
     *
     * @return self
     */
    public static function create(RequestHandlerInterface $requestHandler, array $middlewares = []): self
    {
        $stack = new self($requestHandler);
        foreach ($middlewares as $middleware) {
            $stack = $stack->withPushedMiddleware($middleware);
        }

        return $stack;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (null === $next = $this->peek()) {
            return $this->requestHandler->handle($request);
        }

        return $next->process($request, $this->pop());
    }

    /**
     * Creates a new stack with the given middleware pushed.
     *
     * @param MiddlewareInterface $middleware
     *
     * @return self
     */
    public function withPushedMiddleware(MiddlewareInterface $middleware): self
    {
        $stack = clone $this;
        array_unshift($stack->middlewares, $middleware);

        return $stack;
    }

    /**
     * @return MiddlewareInterface|null
     */
    private function peek()
    {
        return reset($this->middlewares) ?: null;
    }

    /**
     * @return self
     */
    private function pop(): self
    {
        $stack = clone $this;
        array_shift($stack->middlewares);

        return $stack;
    }
}
