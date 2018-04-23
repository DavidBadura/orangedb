<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Adapter;

use DavidBadura\OrangeDb\Type\StringType;
use DavidBadura\OrangeDb\Type\TypeInterface;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class StringTypeTest extends AbstractTypeTest
{
    protected function createType(): TypeInterface
    {
        return new StringType();
    }

    public function transformToPhpProvider()
    {
        yield ['', ''];
        yield ['foo', 'foo'];
    }

    public function transformToDumpProvider()
    {
        yield [null];
        yield ['foo'];
        yield [''];
    }
}
