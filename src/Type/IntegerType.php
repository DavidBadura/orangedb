<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class IntegerType implements TypeInterface
{
    public function transformToPhp($value): int
    {
        return (int)$value;
    }

    public function transformToDump($value): string
    {
        return (string)$value;
    }

    public function getName(): string
    {
        return 'int';
    }
}
