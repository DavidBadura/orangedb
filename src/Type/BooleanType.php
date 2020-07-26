<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class BooleanType implements TypeInterface
{
    public function transformToPhp($value, array $options): bool
    {
        return (bool)$value;
    }

    public function transformToDump($value, array $options): string
    {
        if ($value === null) {
            return 'null';
        }

        return $value ? 'true' : 'false';
    }

    public function getName(): string
    {
        return 'bool';
    }

    public function getXsdType(): string
    {
        return 'xs:boolean';
    }
}
