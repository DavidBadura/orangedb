<?php

namespace DavidBadura\OrangeDb\Adapter;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class JsonAdapter extends AbstractAdapter
{
    /**
     * @param string $type
     * @param string $identifier
     *
     * @return array
     */
    public function load($type, $identifier)
    {
        return json_decode(file_get_contents($this->findFile($type, $identifier, 'json')), true);
    }
}
