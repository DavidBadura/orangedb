<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Adapter;

use DavidBadura\OrangeDb\Type\BooleanType;
use DavidBadura\OrangeDb\Type\TypeInterface;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class BooleanTypeTest extends AbstractTypeTest
{
    protected function createType(): TypeInterface
    {
        return new BooleanType();
    }

    public function transformToPhpProvider()
    {
        yield [true, true];
        yield [false, false];
    }

    public function transformToDumpProvider()
    {
        yield [null];
        yield [true];
        yield [false];
    }
}
