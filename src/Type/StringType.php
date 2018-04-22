<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class StringType implements TypeInterface
{
    public function transformToPhp($value, array $options): string
    {
        return (string)$value;
    }

    public function transformToDump($value, array $options): string
    {
        return "'$value'";
    }

    public function getName(): string
    {
        return 'string';
    }
}
