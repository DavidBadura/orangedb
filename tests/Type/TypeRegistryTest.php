<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Adapter;

use DavidBadura\OrangeDb\Type\TypeInterface;
use DavidBadura\OrangeDb\Type\TypeRegistry;
use PHPUnit\Framework\TestCase;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class TypeRegistryTest extends TestCase
{
    public function testBuiltInTypes()
    {
        $registry = TypeRegistry::createWithBuiltinTypes();

        self::assertCount(6, $registry->all());
        self::assertTrue($registry->has('int'));
    }

    public function testCustomType()
    {
        $registry = new TypeRegistry();

        self::assertCount(0, $registry->all());

        $type = new class implements TypeInterface {
            public function transformToPhp($value, array $options): string
            {
                return $value;
            }

            public function transformToDump($value, array $options): string
            {
                return $value;
            }

            public function getName(): string
            {
                return 'test';
            }
        };

        $registry->addType($type);

        self::assertCount(1, $registry->all());
        self::assertTrue($registry->has('test'));
        self::assertEquals($type, $registry->get('test'));
    }
}
