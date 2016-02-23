<?php

namespace DavidBadura\OrangeDb\Repository;

use DavidBadura\OrangeDb\Metadata\ClassMetadata;
use DavidBadura\OrangeDb\DocumentManager;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class RepositoryFactory
{
    /**
     * @var DocumentRepository[]
     */
    private $repositories = array();

    /**
     * @param DocumentManager $objectManager
     * @param string $class
     * @return DocumentRepository
     */
    public function getRepository(DocumentManager $objectManager, $class)
    {
        $repositoryHash = $objectManager->getMetadataFor($class)->name . spl_object_hash($objectManager);

        if (isset($this->repositories[$repositoryHash])) {
            return $this->repositories[$repositoryHash];
        }

        return $this->repositories[$repositoryHash] = $this->createRepository($objectManager, $class);
    }

    /**
     * @param DocumentManager $objectManager
     * @param string $class
     * @return DocumentRepository
     */
    private function createRepository(DocumentManager $objectManager, $class)
    {
        /* @var $metadata ClassMetadata */
        $metadata = $objectManager->getMetadataFor($class);

        $repositoryClassName = $metadata->repository ?: DocumentRepository::class;

        return new $repositoryClassName($objectManager, $metadata);
    }
}