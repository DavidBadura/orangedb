<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Adapter;

use Symfony\Component\Yaml\Yaml;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class YamlAdapter extends AbstractAdapter
{
    public function __construct(string $directory)
    {
        parent::__construct($directory, 'yaml');
    }

    public function load(string $collection, string $identifier): array
    {
        $content = file_get_contents($this->findFile($collection, $identifier));

        if ($content === false) {
            throw new \RuntimeException();
        }

        return Yaml::parse($content);
    }
}
