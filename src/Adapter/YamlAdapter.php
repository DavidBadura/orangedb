<?php

namespace DavidBadura\OrangeDb\Adapter;

use Symfony\Component\Yaml\Yaml;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class YamlAdapter extends AbstractAdapter
{
    /**
     * @param string $collection
     * @param string $identifier
     *
     * @return array
     */
    public function load($collection, $identifier)
    {
        return Yaml::parse(file_get_contents($this->findFile($collection, $identifier, 'yml')));
    }
}
