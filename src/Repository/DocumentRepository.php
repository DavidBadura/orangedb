<?php

namespace DavidBadura\OrangeDb\Repository;

use DavidBadura\OrangeDb\DocumentManager;
use DavidBadura\OrangeDb\Metadata\ClassMetadata;
use Pinq\Traversable;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class DocumentRepository
{
    /**
     * @var DocumentManager
     */
    protected $manager;

    /**
     * @var ClassMetadata
     */
    private $metadata;

    /**
     * @param DocumentManager $manager
     * @param ClassMetadata $metadata
     */
    public function __construct(DocumentManager $manager, ClassMetadata $metadata)
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

    /**
     * @return object[]
     */
    public function findAll()
    {
        return $this->manager->getDocumentLoader()->loadAll($this->metadata->name);
    }

    /**
     * @return \Pinq\ITraversable
     */
    public function createTraversable()
    {
        return Traversable::from($this->findAll());
    }

    /**
     * @return \Pinq\ITraversable
     */
    public function createQuery()
    {
        return $this->createTraversable();
    }
}