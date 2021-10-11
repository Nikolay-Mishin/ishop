<?php

namespace ishop\libs\collection;

//use \IteratorAggregate;
//use \Countable;
use \Iterator;
use \ArrayAccess;

use \InvalidArgumentException;
//use \Exception;
//use \Traversable;
//use \ArrayIterator;

class GenericCollection<T> implements Iterator, ArrayAccess {

    private $position;

    private $array = [];

    public function __construct() {
        $this->position = 0;
    }

    public function offsetGet($offset): ?T {
        return isset($this->array[$offset]) ? $this->array[$offset] : null;
    }

    public function offsetSet($offset, $value) {
        if (!$value instanceof T) {
            throw new InvalidArgumentException("value must be instance of {T}.");
        }

        if (is_null($offset)) {
            $this->array[] = $value;
        } else {
            $this->array[$offset] = $value;
        }
    }

    public function current(): ?T {
        return $this->array[$this->position];
    }

    public function next() {
        ++$this->position;
    }

    public function key() {
        return $this->position;
    }

    public function valid() {
        return isset($this->array[$this->position]);
    }

    public function rewind() {
        $this->position = 0;
    }

    public function offsetExists($offset) {
        return isset($this->array[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->array[$offset]);
    }

}

$collection = new GenericCollection<Post>();
$collection[] = new Post(1);

// This would throw the InvalidArgumentException.
$collection[] = 'abc';

foreach ($collection as $item) {
    echo "{$item->getId()}\n";
}
