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

Given that you have a nuclear application and you would like to dispatch it wrapped by several [PSR-15] middlewares,
your app must implements [DelegateInterface] or must be decorated with a `DelegateInterface` implementation.

```php
/* @var $delegate DelegateInterface */
$delegate = new YourApp();
```

### Piped dispatch

One way to dispatch your app is to create a `Pipe`, connect the desired middlewares and finally process the server
request through the pipe:
```php
use Ajgarlag\Psr15\Dispatcher\Pipe;

$pipe = Pipe::create()
    ->withConnectedMiddleware(new LogAccessMiddleware())
    ->withConnectedMiddleware(new ThrotleClientMiddleware())
    ->withConnectedMiddleware(new AuthenticateClientMiddleware())
;

$response = $pipe->process($request, $delegate);
```

The `Pipe` class implements itself the PSR-15 [MiddlewareInterface], so it can be nested inside another `Pipe`.


### Stacked dispatch

If you prefer, you can wrap your app delegate into an `Stack` instance, push the desired middlewares and finally process
the server request through the stack. Beware that to achieve the same behavior you must push middlewares in **reverse**
order:
```php
use Ajgarlag\Psr15\Dispatcher\Stack;

$stack = Stack::create($delegate)
    ->withPushedMiddleware(new AuthenticateClientMiddleware())
    ->withPushedMiddleware(new ThrotleClientMiddleware())
    ->withPushedMiddleware(new LogAccessMiddleware())
;

$response = $stack->process($request);
```

The `Stack` class implements itself the PSR-15 [DelegateInterface], so it can be wrapped by another `Stack`.


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

[PSR-15]: https://github.com/http-interop/http-middleware
[DelegateInterface]: https://github.com/http-interop/http-middleware/blob/master/src/DelegateInterface.php
[MiddlewareInterface]: https://github.com/http-interop/http-middleware/blob/master/src/MiddlewareInterface.php
[LICENSE]: LICENSE
[Github issue tracker]: https://github.com/ajgarlag/psr15-dispatcher/issues
[Antonio J. García Lagar]: http://aj.garcialagar.es
[GitHub repository page]: https://github.com/ajgarlag/psr15-dispatcher
[Packagist package page]: https://packagist.org/packages/ajgarlag/psr15-dispatcher
