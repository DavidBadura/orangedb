<?php

namespace DavidBadura\OrangeDb\Repository;

use DavidBadura\OrangeDb\Metadata\ClassMetadata;
use DavidBadura\OrangeDb\ObjectManager;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class RepositoryFactory
{
    /**
     * @var ObjectRepository[]
     */
    private $repositories = array();

    /**
     * @param ObjectManager $objectManager
     * @param string $class
     * @return ObjectRepository
     */
    public function getRepository(ObjectManager $objectManager, $class)
    {
        $repositoryHash = $objectManager->getMetadataFor($class)->name . spl_object_hash($objectManager);

        if (isset($this->repositories[$repositoryHash])) {
            return $this->repositories[$repositoryHash];
        }

        return $this->repositories[$repositoryHash] = $this->createRepository($objectManager, $class);
    }

    /**
     * @param ObjectManager $objectManager
     * @param string $class
     * @return ObjectRepository
     */
    private function createRepository(ObjectManager $objectManager, $class)
    {
        /* @var $metadata ClassMetadata */
        $metadata = $objectManager->getMetadataFor($class);

        $repositoryClassName = $metadata->repository ?: ObjectRepository::class;

        return new $repositoryClassName($objectManager, $metadata);
    }
}