<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Adapter;

use DavidBadura\OrangeDb\Type\FloatType;
use DavidBadura\OrangeDb\Type\TypeInterface;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class FloatTypeTest extends AbstractTypeTest
{
    protected function createType(): TypeInterface
    {
        return new FloatType();
    }

    public function transformToPhpProvider()
    {
        yield [0, 0];
        yield [-20, -20];
        yield [-20.5, -20.5];
        yield [42.01, 42.01];
    }

    public function transformToDumpProvider()
    {
        yield [null];
        yield [0];
        yield [-20];
        yield [-20.5];
        yield [42.01];
    }
}
