<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class IntegerType implements TypeInterface
{
    public function transformToPhp($value, array $options): int
    {
        return (int)$value;
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
        return 'int';
    }

    public function getXsdType(): string
    {
        return 'xs:integer';
    }
}
