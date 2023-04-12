<?php

/*
 * PSR-15 Dispatcher by @ajgarlag
 *
 * Copyright (C) Antonio J. García Lagar <aj@garcialagar.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Ajgarlag\Psr15\Dispatcher;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

trait Psr7FactoryTrait
{
    protected function fakeAServerRequest(array $server = [], string $method = 'GET', string $uri = 'http://example.org'): ServerRequestInterface
    {
        $psr17Factory = new Psr17Factory();

        return $psr17Factory->createServerRequest($method, $uri, $server);
    }

    protected function fakeAResponse(int $code = 200): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        return $psr17Factory->createResponse($code);
    }
}
