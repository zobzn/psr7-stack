<?php

namespace Zobzn\Psr7;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Traversable;

class Stack
{
    protected $items = array();

    public static function create($items = array())
    {
        return new static($items);
    }

    public function __construct($items = array())
    {
        if (is_array($items) || $items instanceof Traversable) {
            foreach ($items as $item) {
                $this->push($item);
            }
        }
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $items = $this->items;

        $cb_next = function (ServerRequestInterface $request, ResponseInterface $response) use (&$items, &$cb_next) {
            if ($item = array_shift($items)) {
                $response = call_user_func($item, $request, $response, $cb_next);
            }

            return $response;
        };

        if ($items) {
            $response = $cb_next($request, $response, $cb_next);
        }

        if ($next) {
            $response = call_user_func($next, $request, $response);
        }

        return $response;
    }

    public function push($value)
    {
        if (is_callable($value)) {
            $this->items[] = $value;
        }

        return $this;
    }
}
