<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Adapter;

use DavidBadura\OrangeDb\Type\TypeInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
abstract class AbstractTypeTest extends TestCase
{
    abstract protected function createType(): TypeInterface;

    abstract public function transformToPhpProvider();

    abstract public function transformToDumpProvider();

    /**
     * @dataProvider transformToPhpProvider
     */
    public function testTransformToPhp($befor, $after, $options = [])
    {
        $type = $this->createType();

        self::assertEquals($after, $type->transformToPhp($befor, $options));
    }

    /**
     * @dataProvider transformToDumpProvider
     */
    public function testTransformToDump($value, $options = [])
    {
        $type = $this->createType();

        $dump = $type->transformToDump($value, $options);
        $var = null;

        eval("\$var = $dump;");

        $this->assertEquals($value, $var);
    }
}
