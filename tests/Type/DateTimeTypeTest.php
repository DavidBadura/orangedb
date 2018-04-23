<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Adapter;

use DavidBadura\OrangeDb\Type\DateTimeType;
use DavidBadura\OrangeDb\Type\TypeInterface;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class DateTimeTypeTest extends AbstractTypeTest
{
    protected function createType(): TypeInterface
    {
        return new DateTimeType();
    }

    public function transformToPhpProvider()
    {
        yield ['2000-01-01 15:15:30', new \DateTime('2000-01-01 15:15:30')];
    }

    public function transformToDumpProvider()
    {
        yield [null];
        yield [new \DateTime('2000-01-01 15:15:30')];
    }
}
