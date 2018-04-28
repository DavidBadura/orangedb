<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Metadata;

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

    public function getIdentifier(object $object)
    {
        return $this->propertyMetadata[$this->identifier]->getValue($object);
    }

    public function merge(MergeableInterface $object)
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
