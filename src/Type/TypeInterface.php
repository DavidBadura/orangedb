<?php

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
interface TypeInterface
{
    public function transformToPhp($value);
    public function getName(): string;
}