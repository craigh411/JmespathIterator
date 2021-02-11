<?php

namespace Humps\Jmespath;

use ArrayAccess;
use Countable;
use Iterator;
use Serializable;
use function JmesPath\search;

class JmespathIterator implements ArrayAccess, Countable, Iterator, Serializable
{

    /**
     * @var array $arr
     */
    private $arr;
    /**
     * @var int $index
     */
    private $index;

    /**
     * JmespathIterator constructor.
     * @param array $arr
     */
    function __construct(array $arr = [])
    {
        $this->arr = $arr;
        $this->index = 0;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        $value = $this->arr[$this->index];
        return (is_array($value)) ? new static($value) : $value;
    }

    /**
     *
     */
    public function next()
    {
        $this->index++;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->arr[$this->index]);
    }

    /**
     *
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        if (is_numeric($offset)) {
            return isset($this->arr[$offset]);
        }

        return !!search($this->parseExpression($offset), $this->arr);
    }

    /**
     * @param mixed $expression
     * @return mixed
     */
    public function offsetGet($expression)
    {
        if (is_numeric($expression)) {
            $value = $this->arr[$expression];
            return (is_array($value)) ? new static($value) : $value;
        }

        $search = search($this->parseExpression($expression), $this->arr);
        return is_array($search) ? new static($search) : $search;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->arr[] = $value;
        } else {
            $this->arr[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->arr[$offset]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->arr);
    }


    /**
     * @return $this
     */
    public function values()
    {
        return new static(array_values($this->arr));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->arr, JSON_PRETTY_PRINT);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->arr;
    }

    /**
     * @return string|null
     */
    public function serialize()
    {
        return serialize([$this->arr, $this->index]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        [
            $this->arr,
            $this->index,
        ] = unserialize($serialized);
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