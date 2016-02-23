<?php

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
interface TypeInterface
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function transformToPhp($value);

    /**
     * @return string
     */
    public function getName();
}