<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class ArrayType implements TypeInterface
{
    public function transformToPhp($value): array
    {
        return (array)$value;
    }

    public function getName(): string
    {
        return 'array';
    }
}
