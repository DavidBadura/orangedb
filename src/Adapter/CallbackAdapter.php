<?php

namespace DavidBadura\OrangeDb\Adapter;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class CallbackAdapter implements AdapterInterface
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @param callable $callback
     */
    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     *
     * @param string $collection
     * @param string $identifier
     *
     * @return array
     */
    public function load($collection, $identifier)
    {
        $callback = $this->callback;

        return $callback($collection, $identifier);
    }
}
