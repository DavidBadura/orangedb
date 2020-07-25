<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Repository;

use DavidBadura\OrangeDb\DocumentManager;
use DavidBadura\OrangeDb\Loader\LoaderInterface;
use DavidBadura\OrangeDb\Metadata\ClassMetadata;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class RepositoryFactory
{
    /**
     * @var DocumentRepository[]
     */
    private array $repositories = [];

    public function getRepository(
        DocumentManager $objectManager,
        LoaderInterface $loader,
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
        LoaderInterface $loader,
        string $class
    ): DocumentRepository {
        /* @var $metadata ClassMetadata */
        $metadata = $objectManager->getMetadataFor($class);

        $repositoryClassName = $metadata->repository ?: DocumentRepository::class;
        $documentRepository = new $repositoryClassName($objectManager, $metadata, $loader);

        if (!$documentRepository instanceof DocumentRepository) {
            throw new \RuntimeException();
        }

        return $documentRepository;
    }
}
