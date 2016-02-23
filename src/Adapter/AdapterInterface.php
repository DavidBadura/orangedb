<?php

namespace DavidBadura\OrangeDb\Adapter;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
interface AdapterInterface
{
    /**
     * @param string $collection
     * @param string $identifier
     *
     * @return array
     */
    public function load($collection, $identifier);
}
