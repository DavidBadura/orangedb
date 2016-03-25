<?php

namespace DavidBadura\OrangeDb\NamingStrategy;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
interface NamingStrategyInterface
{
    /**
     * @param string $property
     * @return string
     */
    public function propertyToFieldName($property);
}