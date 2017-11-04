<?php

namespace DavidBadura\OrangeDb\Collection;

/**
 * @author David Badura <david.badura@i22.de>
 */
class ObjectCollection implements \ArrayAccess, \Iterator
{
    private $list;

    public function __construct()
    {
        $this->list = new \SplObjectStorage;
    }

    /**
     * @param object $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->list->offsetExists($offset);
    }

    /**
     * @param object $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->list->offsetGet($offset);
    }

    /**
     * @param object $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->list->offsetSet($offset, $value);
    }

    /**
     * @param object $offset
     */
    public function offsetUnset($offset)
    {
        $this->list->offsetUnset($offset);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->list[$this->list->current()];
    }

    public function next()
    {
        $this->list->next();
    }

    /**
     * @return object
     */
    public function key()
    {
        return $this->list->current();
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return $this->list->valid();
    }

    public function rewind()
    {
        $this->list->rewind();
    }
}