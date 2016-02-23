<?php

namespace DavidBadura\OrangeDb\Repository;

use DavidBadura\OrangeDb\Metadata\ClassMetadata;
use DavidBadura\OrangeDb\ObjectManager;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class ObjectRepository
{
    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var ClassMetadata
     */
    private $metadata;

    /**
     * @param ObjectManager $manager
     * @param ClassMetadata $metadata
     */
    public function __construct(ObjectManager $manager, ClassMetadata $metadata)
    {
        $this->manager = $manager;
        $this->metadata = $metadata;
    }

    /**
     * @param string $identifer
     * @return object
     */
    public function find($identifer)
    {
        return $this->manager->find($this->metadata->name, $identifer);
    }
}