<?php

namespace tests\Ajgarlag\Psr15\Dispatcher;

use Ajgarlag\Psr15\Dispatcher\Pipe;
use Http\Factory\Diactoros\ServerRequestFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PipeTest extends TestCase
{
    /** @var Pipe */
    private $pipe;

    public function setUp()
    {
        $this->pipe = Pipe::create([]);
    }

    public function testCreate()
    {
        $this->assertInstanceOf(Pipe::class, Pipe::create([]));
    }

    public function testWithConnectedMiddleware()
    {
        $middleware = new Middleware();
        $pipe = $this->pipe->withConnectedMiddleware($middleware);
        $this->assertInstanceOf(Pipe::class, $pipe);
    }

    public function testProcess()
    {
        $request = (new ServerRequestFactory)->createServerRequest('GET', '/');
        $requestHandler = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
        $responseInterface = $this->pipe->process($request, $requestHandler);
        $this->assertInstanceOf(ResponseInterface::class, $responseInterface);
    }
}
