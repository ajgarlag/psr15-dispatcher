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
    /**
     * @param array  $server
     * @param string $method
     * @param string $uri
     *
     * @return ServerRequestInterface
     */
    protected function fakeAServerRequest($server = [], $method = 'GET', $uri = 'http://example.org')
    {
        $requestFactory = new Psr17Factory();
        $request = $requestFactory->createServerRequest($method, $uri, $server);

        return $request;
    }

    /**
     * @param int $code
     *
     * @return ResponseInterface
     */
    protected function fakeAResponse($code = 200)
    {
        $factory = new Psr17Factory();
        $response = $factory->createResponse($code);

        return $response;
    }
}
