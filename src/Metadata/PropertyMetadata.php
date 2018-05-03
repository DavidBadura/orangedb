<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Metadata;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class PropertyMetadata extends \Metadata\PropertyMetadata
{
    public const REFERENCE_ONE = 'one';
    public const REFERENCE_MANY = 'many';

    public const EMBED_ONE = 'one';
    public const EMBED_MANY = 'many';

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
     * @var array|string
     */
    public $mapping;

    /**
     * @var array
     */
    public $options = [];

    /**
     * @var bool
     */
    public $nullable;

    public function setValue($obj, $value): void
    {
        $this->reflection->setValue($obj, $value);
    }
}
