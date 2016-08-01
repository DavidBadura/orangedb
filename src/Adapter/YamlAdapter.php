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
     * @param string $directory
     */
    public function __construct($directory)
    {
        parent::__construct($directory, 'yml');
    }

    /**
     * @param string $collection
     * @param string $identifier
     *
     * @return array
     *
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    public function load($collection, $identifier)
    {
        return Yaml::parse(file_get_contents($this->findFile($collection, $identifier)));
    }
}
