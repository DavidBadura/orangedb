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

    public function getIdentifier(object $object)
    {
        return $this->propertyMetadata[$this->identifier]->getValue($object);
    }

    /**
     * @param MergeableInterface|ClassMetadata $object
     */
    public function merge(MergeableInterface $object)
    {
        parent::merge($object);

        $this->collection = $object->collection;
        $this->identifier = $object->identifier;
        $this->repository = $object->repository;
    }
}
