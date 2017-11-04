<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class BooleanType implements TypeInterface
{
    public function transformToPhp($value): bool
    {
        return (bool)$value;
    }

    public function getName(): string
    {
        return 'bool';
    }
}
