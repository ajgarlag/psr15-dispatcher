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

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Stack implements DelegateInterface
{
    /**
     * @var DelegateInterface
     */
    private $delegate;

    /**
     * @var MiddlewareInterface[]
     */
    private $middlewares = [];

    /**
     * @param DelegateInterface $delegate
     */
    private function __construct(DelegateInterface $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * Constructs an stack without any middleware.
     *
     * @param DelegateInterface $delegate
     *
     * @return self
     */
    public static function create(DelegateInterface $delegate)
    {
        return new self($delegate);
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request)
    {
        if (null === $next = $this->peek()) {
            return $this->delegate->process($request);
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
    public function withMiddleware(MiddlewareInterface $middleware)
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
    private function pop()
    {
        $stack = clone $this;
        array_shift($stack->middlewares);

        return $stack;
    }
}
