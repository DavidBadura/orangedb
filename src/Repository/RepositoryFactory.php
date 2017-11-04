<?php

namespace DavidBadura\OrangeDb\Repository;

use DavidBadura\OrangeDb\DocumentLoader;
use DavidBadura\OrangeDb\DocumentManager;
use DavidBadura\OrangeDb\Metadata\ClassMetadata;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class RepositoryFactory
{
    /**
     * @var DocumentRepository[]
     */
    private $repositories = array();

    public function getRepository(
        DocumentManager $objectManager,
        DocumentLoader $loader,
        string $class
    ): DocumentRepository {
        $repositoryHash = $objectManager->getMetadataFor($class)->name.spl_object_hash($objectManager);

        if (isset($this->repositories[$repositoryHash])) {
            return $this->repositories[$repositoryHash];
        }

        return $this->repositories[$repositoryHash] = $this->createRepository($objectManager, $loader, $class);
    }

    private function createRepository(
        DocumentManager $objectManager,
        DocumentLoader $loader,
        string $class
    ): DocumentRepository {
        /* @var $metadata ClassMetadata */
        $metadata = $objectManager->getMetadataFor($class);

        $repositoryClassName = $metadata->repository ?: DocumentRepository::class;

        return new $repositoryClassName($objectManager, $metadata, $loader);
    }
}