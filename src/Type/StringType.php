<?php

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class StringType implements TypeInterface
{
    /**
     * @param mixed $value
     * @return string
     */
    public function transformToPhp($value)
    {
        return (string)$value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'string';
    }
}