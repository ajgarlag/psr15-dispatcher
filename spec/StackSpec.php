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

use Ajgarlag\Psr15\Dispatcher\Stack;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;

class StackSpec extends ObjectBehavior
{
    use Psr7FactoryTrait;

    public function let(DelegateInterface $delegate)
    {
        $this->beConstructedThrough('create', [$delegate]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Stack::class);
    }

    public function it_is_a_delegate()
    {
        $this->shouldImplement(DelegateInterface::class);
    }

    public function it_delegates_to_inner_delegate(DelegateInterface $delegate)
    {
        $response = $this->fakeAResponse();

        $delegate->process(Argument::type(RequestInterface::class))->willReturn($response)->shouldBeCalled();

        $this->process($this->fakeAServerRequest())->shouldReturn($response);
    }

    public function it_creates_a_new_stack_when_a_middleware_is_pushed(MiddlewareInterface $middleware)
    {
        $stack = $this->withPushedMiddleware($middleware);

        $stack->shouldBeAnInstanceOf(Stack::class);
        $stack->shouldNotBe($this);
    }

    public function it_can_create_a_new_stack_with_middlewares_pushed(MiddlewareInterface $innerMiddleware, MiddlewareInterface $outerMiddleware, DelegateInterface $delegate)
    {
        $response = $this->fakeAResponse();

        $delegate->process(Argument::type(RequestInterface::class))->willReturn($response)->shouldBeCalledTimes(2);

        $outerMiddleware->process(Argument::type(ServerRequestInterface::class), Argument::type(DelegateInterface::class))->will(
                function ($args) use ($innerMiddleware) {
                    $innerMiddleware->process(Argument::type(ServerRequestInterface::class), Argument::type(DelegateInterface::class))->will(
                            function ($args) {
                                return $args[1]->process($args[0]);
                            }
                        )
                        ->shouldBeCalledTimes(2)
                    ;

                    return $args[1]->process($args[0]);
                }
            )
            ->shouldBeCalledTimes(2)
        ;

        $this->beConstructedThrough('create', [$delegate, [$innerMiddleware, $outerMiddleware]]);

        $this->process($this->fakeAServerRequest())->shouldReturn($response);
        $this->process($this->fakeAServerRequest())->shouldReturn($response);
    }

    public function it_processes_requests_through_pushed_middlewares_in_order(MiddlewareInterface $innerMiddleware, MiddlewareInterface $outerMiddleware, DelegateInterface $delegate)
    {
        $response = $this->fakeAResponse();

        $delegate->process(Argument::type(RequestInterface::class))->willReturn($response)->shouldBeCalledTimes(2);

        $outerMiddleware->process(Argument::type(ServerRequestInterface::class), Argument::type(DelegateInterface::class))->will(
                function ($args) use ($innerMiddleware) {
                    $innerMiddleware->process(Argument::type(ServerRequestInterface::class), Argument::type(DelegateInterface::class))->will(
                            function ($args) {
                                return $args[1]->process($args[0]);
                            }
                        )
                        ->shouldBeCalledTimes(2)
                    ;

                    return $args[1]->process($args[0]);
                }
            )
            ->shouldBeCalledTimes(2)
        ;

        $stack = $this->withPushedMiddleware($innerMiddleware)->withPushedMiddleware($outerMiddleware);

        $stack->process($this->fakeAServerRequest())->shouldReturn($response);
        $stack->process($this->fakeAServerRequest())->shouldReturn($response);
    }
}
