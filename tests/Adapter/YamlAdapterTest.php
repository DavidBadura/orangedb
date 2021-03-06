<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Adapter;

use DavidBadura\OrangeDb\Adapter\AdapterInterface;
use DavidBadura\OrangeDb\Adapter\YamlAdapter;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class YamlAdapterTest extends AbstractAdapterTest
{
    public function createAdapter(): AdapterInterface
    {
        return new YamlAdapter(__DIR__.'/../_files/yaml');
    }
}
