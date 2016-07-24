# zobzn/psr7-stack

[![Build Status](https://img.shields.io/travis/zobzn/psr7-stack/master.svg?style=flat-square)](https://travis-ci.org/zobzn/psr7-stack)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

PSR-7 HTTP Middleware Stack

## Installation

```bash
composer require zobzn/psr7-stack
```

## Basic Usage

```php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

require_once __DIR__ . '/vendor/autoload.php';

$stack = new \Zobzn\Stack();
$stack->push(function (ServerRequestInterface $request, ResponseInterface $response, callable $next = null) {
    // do something with request and/or response
    return $next ? $next($request, $response) : $response;
});
$stack->push(function (ServerRequestInterface $request, ResponseInterface $response, callable $next = null) {
    // do something else with request and/or response
    return $next ? $next($request, $response) : $response;
});

$request  = new SomeServerRequestImplementation();
$response = new SomeResponseImplementation();

// execute middlewares on given request and response, and get final response
$response = $stack->__invoke($request, $response);
```
