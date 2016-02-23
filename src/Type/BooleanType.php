<?php

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class BooleanType implements TypeInterface
{
    /**
     * @param mixed $value
     * @return int
     */
    public function transformToPhp($value)
    {
        return (bool)$value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bool';
    }
}