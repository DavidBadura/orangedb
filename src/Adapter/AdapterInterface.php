<?php

namespace DavidBadura\OrangeDb\Adapter;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
interface AdapterInterface
{
    /**
     * @param string $type
     * @param string $identifier
     *
     * @return array
     */
    public function load($type, $identifier);
}
