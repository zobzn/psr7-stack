<?php

namespace Zobzn\Test\Psr7;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zobzn\Psr7\Stack;

class StackTest extends \PHPUnit_Framework_TestCase
{
    public function testFirst()
    {
        $request  = ServerRequestFactory::fromGlobals();
        $response = new Response();

        $stack = new Stack();

        $this->assertSame($response, $stack($request, $response));
    }

    public function testSecond()
    {
        $request  = ServerRequestFactory::fromGlobals();
        $response = new Response();

        $stack = new Stack();
        $stack->push(function (ServerRequestInterface $request, ResponseInterface $response, callable $next = null) {
            return $next ? $next($request, $response) : $response;
        });

        $this->assertSame($response, $stack($request, $response));
    }

    public function testThird()
    {
        $request  = ServerRequestFactory::fromGlobals();
        $response = new Response();

        $stack = new Stack();
        $stack->push(function (ServerRequestInterface $request, ResponseInterface $response, callable $next = null) {
            $response->getBody()->write(1);

            return $next ? $next($request, $response) : $response;
        });
        $stack->push(function (ServerRequestInterface $request, ResponseInterface $response, callable $next = null) {
            $response->getBody()->write(2);

            return $next ? $next($request, $response) : $response;
        });

        $this->assertSame('12', (string) $stack->__invoke($request, $response)->getBody());
    }

    public function testFourth()
    {
        $request  = ServerRequestFactory::fromGlobals();
        $response = new Response();

        $stack = new Stack();
        $stack->push(function (ServerRequestInterface $request, ResponseInterface $response, callable $next = null) {
            $response->getBody()->write(1);

            return $response;
        });
        $stack->push(function (ServerRequestInterface $request, ResponseInterface $response, callable $next = null) {
            $response->getBody()->write(2);

            return $next ? $next($request, $response) : $response;
        });

        $this->assertSame('1', (string) $stack->__invoke($request, $response)->getBody());
    }
}
