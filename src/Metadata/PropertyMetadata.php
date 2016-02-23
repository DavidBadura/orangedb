<?php

namespace DavidBadura\OrangeDb\Metadata;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class PropertyMetadata extends \Metadata\PropertyMetadata
{
    const REFERENCE_ONE = 'one';
    const REFERENCE_MANY = 'many';

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var string
     */
    public $target;
}