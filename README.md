Psr15 Dispatcher
================

The Psr15 Dispatcher component allows you to dispatch [PSR-15] middlewares.

[![Build Status](https://travis-ci.org/ajgarlag/psr15-dispatcher.png?branch=master)](https://travis-ci.org/ajgarlag/psr15-dispatcher)
[![Latest Stable Version](https://poser.pugx.org/ajgarlag/psr15-dispatcher/v/stable.png)](https://packagist.org/packages/ajgarlag/psr15-dispatcher)
[![Latest Unstable Version](https://poser.pugx.org/ajgarlag/psr15-dispatcher/v/unstable.png)](https://packagist.org/packages/ajgarlag/psr15-dispatcher)
[![Total Downloads](https://poser.pugx.org/ajgarlag/psr15-dispatcher/downloads.png)](https://packagist.org/packages/ajgarlag/psr15-dispatcher)
[![Montly Downloads](https://poser.pugx.org/ajgarlag/psr15-dispatcher/d/monthly.png)](https://packagist.org/packages/ajgarlag/psr15-dispatcher)
[![Daily Downloads](https://poser.pugx.org/ajgarlag/psr15-dispatcher/d/daily.png)](https://packagist.org/packages/ajgarlag/psr15-dispatcher)
[![License](https://poser.pugx.org/ajgarlag/psr15-dispatcher/license.png)](https://packagist.org/ajgarlag/psr15-dispatcher)


Installation
------------

To install the latest stable version of this component, open a console and execute the following command:
```
$ composer require ajgarlag/psr15-dispatcher
```


Usage
-----

At first, you must have an app that implements [RequestHandlerInterface] or is wrapped in a
`RequestHandlerInterface` implementation, that you would like to dispatch decorated with several [PSR-15] middlewares.

```php
/* @var $requestHandler RequestHandlerInterface */
$requestHandler = new YourApp();
```

Now, you can choose between a `Pipe` or a `Stack` to dispatch your app.

### Pipe dispatch

With this option, you create a `Pipe`, connect the desired middlewares and finally process the server
request through the pipe, passing your app as request handler:

```php
use Ajgarlag\Psr15\Dispatcher\Pipe;

$pipe = Pipe::create()
    ->withConnectedMiddleware(new FirstMiddleware())
    ->withConnectedMiddleware(new MiddleMiddleware())
    ->withConnectedMiddleware(new LastMiddleware())
;

$response = $pipe->process($request, $requestHandler);
```

The `Pipe` class implements itself the PSR-15 [MiddlewareInterface], so it can be connected to another `Pipe`.


#### Pipe initialization

You can pass a FIFO array of middlewares to initialize the `Pipe`:

```php
$pipe = Pipe::create([
    new FirstMiddleware(),
    new MiddleMiddleware(),
    new LastMiddleware(),
]);
```

### Stack dispatch

With this option, you wrap your app request handler into an `Stack` instance, push the desired middlewares and finally process
the server request through the stack. Beware that to achieve the same behavior that in the previous `Pipe` you must push
middlewares in **reverse** order:

```php
use Ajgarlag\Psr15\Dispatcher\Stack;

$stack = Stack::create($requestHandler)
    ->withPushedMiddleware(new LastMiddleware())
    ->withPushedMiddleware(new MiddleMiddleware())
    ->withPushedMiddleware(new FirstMiddleware())
;

$response = $stack->handle($request);
```

The `Stack` class implements itself the PSR-15 [RequestHandlerInterface], so it can be wrapped by another `Stack`.

#### Stack initialization

You can pass a LIFO array of middlewares to initialize the `Stack`:

```php
$stack = Stack::create($requestHandler, [
    new LastMiddleware(),
    new MiddleMiddleware(),
    new FirstMiddleware(),
]);
```

#### Pipe pushed onto the stack

My preferred option is to build a `Pipe` with middlewares connected in natural order, and then, push it onto the stack,
but this is a matter of taste:

```php
$stack = Stack::create($requestHandler);
$pipe = Pipe::create()
    ->withConnectedMiddleware(new FirstMiddleware())
    ->withConnectedMiddleware(new MiddleMiddleware())
    ->withConnectedMiddleware(new LastMiddleware())
;

$application = $stack->withPushedMiddleware($pipe);
```


License
-------

This component is under the MIT license. See the complete license in the [LICENSE] file.


Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker].


Author Information
------------------

Developed with ♥ by [Antonio J. García Lagar].

If you find this component useful, please add a ★ in the [GitHub repository page] and/or the [Packagist package page].

[PSR-15]: https://www.php-fig.org/psr/psr-15/
[RequestHandlerInterface]: https://www.php-fig.org/psr/psr-15/#21-psrhttpserverrequesthandlerinterface
[MiddlewareInterface]: https://www.php-fig.org/psr/psr-15/#22-psrhttpservermiddlewareinterface
[LICENSE]: LICENSE
[Github issue tracker]: https://github.com/ajgarlag/psr15-dispatcher/issues
[Antonio J. García Lagar]: http://aj.garcialagar.es
[GitHub repository page]: https://github.com/ajgarlag/psr15-dispatcher
[Packagist package page]: https://packagist.org/packages/ajgarlag/psr15-dispatcher
