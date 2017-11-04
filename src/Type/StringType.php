<?php

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class StringType implements TypeInterface
{
    public function transformToPhp($value): string
    {
        return (string)$value;
    }

    public function getName(): string
    {
        return 'string';
    }
}