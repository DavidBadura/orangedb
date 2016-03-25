<?php

namespace DavidBadura\OrangeDb\Event;

use DavidBadura\OrangeDb\DocumentManager;
use DavidBadura\OrangeDb\Metadata\ClassMetadata;
use Symfony\Component\EventDispatcher\Event;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class DocumentEvent extends Event
{
    /**
     * @var DocumentManager
     */
    private $manager;

    /**
     * @var object
     */
    private $document;

    /**
     * @var ClassMetadata
     */
    private $metadata;

    /**
     * @param DocumentManager $manager
     * @param ClassMetadata $metadata
     * @param $document
     */
    public function __construct(DocumentManager $manager, ClassMetadata $metadata, $document)
    {
        $this->metadata = $metadata;
        $this->document = $document;
        $this->manager = $manager;
    }

    /**
     * @return DocumentManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @return object
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @return ClassMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}