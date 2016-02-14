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
     * @param string $type
     * @param string $identifier
     *
     * @return array
     */
    public function load($type, $identifier)
    {
        return Yaml::parse(file_get_contents($this->findFile($type, $identifier, 'yml')));
    }
}
