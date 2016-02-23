<?php

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class IntegerType implements TypeInterface
{
    /**
     * @param mixed $value
     * @return int
     */
    public function transformToPhp($value)
    {
        return (int)$value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'int';
    }
}