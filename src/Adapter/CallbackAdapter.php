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
     * @param string $type
     * @param string $identifier
     *
     * @return array
     */
    public function load($type, $identifier)
    {
        return $this->callback($type, $identifier);
    }
}
