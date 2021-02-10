<?php

namespace Humps\Jmespath;

use ArrayAccess;
use Countable;
use Iterator;
use function JmesPath\search;

class JmespathIterator implements ArrayAccess, Countable, Iterator
{

    private $arr;

    function __construct(array $arr = [])
    {
        $this->arr = $arr;
    }

    public function current()
    {
        // TODO: Implement current() method.
    }

    public function next()
    {
        // TODO: Implement next() method.
    }

    public function key()
    {
        // TODO: Implement key() method.
    }

    public function valid()
    {
        // TODO: Implement valid() method.
    }

    public function rewind()
    {
        // TODO: Implement rewind() method.
    }

    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    public function offsetGet($expression)
    {
        if (is_numeric($expression)) {
            return $this->arr[$expression];
        }

        return search($this->parseExpression($expression), $this->arr);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->arr[] = $value;
        } else {
            $this->arr[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }

    public function count()
    {
        return count($this->arr);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->arr, JSON_PRETTY_PRINT);
    }

    /**
     * @param $expression
     * @return string
     */
    protected function parseExpression($expression)
    {
        if (preg_match("/^(?!\[)(\\*|([0-9]*:{1,2}[\-0-9]*))/", $expression)) {
            return "[{$expression}]";
        }

        return $expression;
    }
}