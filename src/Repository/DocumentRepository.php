<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Repository;

use DavidBadura\OrangeDb\DocumentManager;
use DavidBadura\OrangeDb\Loader\LoaderInterface;
use DavidBadura\OrangeDb\Metadata\ClassMetadata;
use Pinq\ITraversable;
use Pinq\Traversable;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class DocumentRepository
{
    protected $manager;
    private $metadata;
    private $loader;

    public function __construct(DocumentManager $manager, ClassMetadata $metadata, LoaderInterface $loader)
    {
        $this->manager = $manager;
        $this->metadata = $metadata;
        $this->loader = $loader;
    }

    public function find(string $identifer): object
    {
        return $this->manager->find($this->metadata->name, $identifer);
    }

    public function findAll(): array
    {
        return $this->loader->loadAll($this->metadata->name);
    }

    public function createTraversable(): ITraversable
    {
        return Traversable::from($this->findAll());
    }

    public function createQuery(): ITraversable
    {
        return $this->createTraversable();
    }
}
