<?php

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class ArrayType implements TypeInterface
{
    /**
     * @param mixed $value
     * @return array
     */
    public function transformToPhp($value)
    {
        return (array)$value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'array';
    }
}