<?php

namespace DavidBadura\OrangeDb\Adapter;

use Symfony\Component\Yaml\Yaml;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class YamlAdapter extends AbstractAdapter
{
    public function __construct(string $directory)
    {
        parent::__construct($directory, 'yml');
    }

    public function load(string $collection, string $identifier): array
    {
        return Yaml::parse(file_get_contents($this->findFile($collection, $identifier)));
    }
}
