<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class FloatType implements TypeInterface
{
    public function transformToPhp($value, array $options): float
    {
        return (float)$value;
    }

    public function transformToDump($value, array $options): string
    {
        if ($value === null) {
            return 'null';
        }

        return (string)$value;
    }

    public function getName(): string
    {
        return 'float';
    }
}
