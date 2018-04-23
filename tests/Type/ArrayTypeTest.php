<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Adapter;

use DavidBadura\OrangeDb\Type\ArrayType;
use DavidBadura\OrangeDb\Type\TypeInterface;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class ArrayTypeTest extends AbstractTypeTest
{
    protected function createType(): TypeInterface
    {
        return new ArrayType();
    }

    public function transformToPhpProvider()
    {
        yield [[], []];
        yield [[1, 'test'], [1, 'test']];
    }

    public function transformToDumpProvider()
    {
        yield [[]];
        yield [null];
        yield [[1, 'test']];
    }
}
