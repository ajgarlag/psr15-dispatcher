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

use Ajgarlag\Psr15\Dispatcher\Pipe;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PipeSpec extends ObjectBehavior
{
    use Psr7FactoryTrait;

    public function let()
    {
        $this->beConstructedThrough('create');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Pipe::class);
    }

    public function it_is_a_middleware()
    {
        $this->shouldImplement(MiddlewareInterface::class);
    }

    public function it_delegates_to_passed_request_handler(RequestHandlerInterface $requestHandler)
    {
        $response = $this->fakeAResponse();

        $requestHandler->handle(Argument::type(ServerRequestInterface::class))->willReturn($response)->shouldBeCalled();

        $this->process($this->fakeAServerRequest(), $requestHandler)->shouldReturn($response);
    }

    public function it_creates_a_new_pipeline_when_a_middleware_is_connected(MiddlewareInterface $middleware)
    {
        $pipe = $this->withConnectedMiddleware($middleware);

        $pipe->shouldBeAnInstanceOf(Pipe::class);
        $pipe->shouldNotBe($this);
    }

    public function it_can_create_a_new_pipeline_with_middlewares_connected(MiddlewareInterface $firstMiddleware, MiddlewareInterface $lastMiddleware, RequestHandlerInterface $requestHandler)
    {
        $response = $this->fakeAResponse();

        $requestHandler->handle(Argument::type(ServerRequestInterface::class))->willReturn($response)->shouldBeCalledTimes(2);

        $firstMiddleware->process(Argument::type(ServerRequestInterface::class), Argument::type(RequestHandlerInterface::class))->will(
            function ($args) use ($lastMiddleware) {
                    $lastMiddleware->process(Argument::type(ServerRequestInterface::class), Argument::type(RequestHandlerInterface::class))->will(
                        function ($args) {
                                return $args[1]->handle($args[0]);
                            }
                    )
                        ->shouldBeCalledTimes(2)
                    ;

                    return $args[1]->handle($args[0]);
                }
        )
            ->shouldBeCalledTimes(2)
        ;

        $this->beConstructedThrough('create', [[$firstMiddleware, $lastMiddleware]]);

        $this->process($this->fakeAServerRequest(), $requestHandler)->shouldReturn($response);
        $this->process($this->fakeAServerRequest(), $requestHandler)->shouldReturn($response);
    }

    public function it_processes_requests_through_piped_middlewares_in_order(MiddlewareInterface $firstMiddleware, MiddlewareInterface $lastMiddleware, RequestHandlerInterface $requestHandler)
    {
        $response = $this->fakeAResponse();

        $requestHandler->handle(Argument::type(ServerRequestInterface::class))->willReturn($response)->shouldBeCalledTimes(2);

        $firstMiddleware->process(Argument::type(ServerRequestInterface::class), Argument::type(RequestHandlerInterface::class))->will(
            function ($args) use ($lastMiddleware) {
                    $lastMiddleware->process(Argument::type(ServerRequestInterface::class), Argument::type(RequestHandlerInterface::class))->will(
                        function ($args) {
                                return $args[1]->handle($args[0]);
                            }
                    )
                        ->shouldBeCalledTimes(2)
                    ;

                    return $args[1]->handle($args[0]);
                }
        )
            ->shouldBeCalledTimes(2)
        ;

        $pipe = $this->withConnectedMiddleware($firstMiddleware)->withConnectedMiddleware($lastMiddleware);

        $pipe->process($this->fakeAServerRequest(), $requestHandler)->shouldReturn($response);
        $pipe->process($this->fakeAServerRequest(), $requestHandler)->shouldReturn($response);
    }
}
