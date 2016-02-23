<?php

namespace DavidBadura\OrangeDb\Metadata;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class PropertyMetadata extends \Metadata\PropertyMetadata
{
    const REFERENCE_ONE = 'one';
    const REFERENCE_MANY = 'many';
    const REFERENCE_KEY = 'key';

    const EMBED_ONE = 'one';
    const EMBED_MANY = 'many';

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
    public $embed;

    /**
     * @var string
     */
    public $target;

    /**
     * @var array
     */
    public $mapping;

    /**
     * @var array
     */
    public $value;
}