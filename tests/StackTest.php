<?php

use Ajgarlag\Psr15\Dispatcher\Stack;
use Http\Factory\Diactoros\ServerRequestFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use tests\Ajgarlag\Psr15\Dispatcher\Middleware;

class StackTest extends TestCase
{
    /** @var Stack */
    private $stack;

    public function setUp()
    {
        $requestHandler = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
        $this->stack = Stack::create($requestHandler);
    }

    public function testCreate()
    {
        $requestHandler = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
        $stack = Stack::create($requestHandler);
        $this->assertInstanceOf(Stack::class, $stack);
    }

    public function testHandle()
    {
        $request = (new ServerRequestFactory)->createServerRequest('GET', '/');
        $response = $this->stack->handle($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testWithPushedMiddleware()
    {
        $middleware = new Middleware();
        $stack = $this->stack->withPushedMiddleware($middleware);
        $this->assertInstanceOf(Stack::class, $stack);
    }
}
