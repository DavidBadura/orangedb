<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Metadata;

use DavidBadura\OrangeDb\DocumentMetadataException;
use Metadata\MergeableClassMetadata;
use Metadata\MergeableInterface;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class ClassMetadata extends MergeableClassMetadata
{
    /**
     * @var string
     */
    public $collection;

    /**
     * @var string|null
     */
    public $identifier;

    /**
     * @var string
     */
    public $repository;

    /**
     * @var string
     */
    public $discriminatorColumn;

    /**
     * @var array
     */
    public $discriminatorMap;

    /**
     * @var string
     */
    public $discriminatorMapCallback;

    public function getIdentifier(object $object)
    {
        if (!$this->identifier) {
            throw new DocumentMetadataException(sprintf('missing identifier on class %s', $this->name));
        }

        if (!isset($this->propertyMetadata[$this->identifier])) {
            throw new DocumentMetadataException(
                sprintf(
                    'property "%s" as identifier on class %s not existis.',
                    $this->identifier,
                    $this->name
                )
            );
        }

        return $this->propertyMetadata[$this->identifier]->getValue($object);
    }

    public function merge(MergeableInterface $object): void
    {
        if (!$object instanceof self) {
            throw new \RuntimeException();
        }

        parent::merge($object);

        if ($object->collection) {
            $this->collection = $object->collection;
        }

        $this->identifier = $object->identifier;
        $this->repository = $object->repository;

        if ($object->discriminatorMap) {
            $this->discriminatorMap = $object->discriminatorMap;
        }

        if ($object->discriminatorColumn) {
            $this->discriminatorColumn = $object->discriminatorColumn;
        }
    }
}
