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

    public ?string $fieldName = null;
    public ?string $type = null;
    public ?string $reference = null;
    public ?string $embed = null;
    public ?string $target = null;

    /**
     * @var mixed|array|string|null
     */
    public $mapping = null;

    public array $options = [];
    public bool $nullable = false;

    /**
     * @param object $obj
     * @param mixed $value
     */
    public function setValue($obj, $value): void
    {
        $this->reflection->setValue($obj, $value);
    }
}
