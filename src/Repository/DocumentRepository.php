<?php

namespace DavidBadura\OrangeDb\Repository;

use DavidBadura\OrangeDb\DocumentManager;
use DavidBadura\OrangeDb\Metadata\ClassMetadata;

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
}