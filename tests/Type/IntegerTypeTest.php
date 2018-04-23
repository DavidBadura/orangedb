<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Adapter;

use DavidBadura\OrangeDb\Type\IntegerType;
use DavidBadura\OrangeDb\Type\TypeInterface;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class IntegerTypeTest extends AbstractTypeTest
{
    protected function createType(): TypeInterface
    {
        return new IntegerType();
    }

    public function transformToPhpProvider()
    {
        yield [0, 0];
        yield [-20, -20];
        yield [42, 42];
    }

    public function transformToDumpProvider()
    {
        yield [null];
        yield [0];
        yield [-20];
        yield [42];
    }
}
