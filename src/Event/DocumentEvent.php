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
    private $manager;
    private $document;
    private $metadata;

    public function __construct(DocumentManager $manager, ClassMetadata $metadata, $document)
    {
        $this->metadata = $metadata;
        $this->document = $document;
        $this->manager = $manager;
    }

    public function getManager(): DocumentManager
    {
        return $this->manager;
    }

    public function getDocument()
    {
        return $this->document;
    }

    public function getMetadata(): ClassMetadata
    {
        return $this->metadata;
    }
}