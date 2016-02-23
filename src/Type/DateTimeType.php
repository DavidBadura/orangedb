<?php

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class DateTimeType implements TypeInterface
{
    /**
     * @param mixed $value
     * @return \DateTime
     */
    public function transformToPhp($value)
    {
        return new \DateTime($value);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'datetime';
    }
}