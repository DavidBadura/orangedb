<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Adapter;

use DavidBadura\OrangeDb\Adapter\AdapterInterface;
use DavidBadura\OrangeDb\Adapter\JsonAdapter;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class JsonAdapterTest extends AbstractAdapterTest
{
    public function createAdapter(): AdapterInterface
    {
        return new JsonAdapter(__DIR__.'/../_files/json');
    }
}
