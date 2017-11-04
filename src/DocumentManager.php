<?php

namespace DavidBadura\OrangeDb;

use DavidBadura\OrangeDb\Adapter\AdapterInterface;
use DavidBadura\OrangeDb\Exception\DocumentMetadataException;
use DavidBadura\OrangeDb\Metadata\ClassMetadata;
use DavidBadura\OrangeDb\Metadata\Driver\AnnotationDriver;
use DavidBadura\OrangeDb\Repository\DocumentRepository;
use DavidBadura\OrangeDb\Repository\RepositoryFactory;
use DavidBadura\OrangeDb\Type\TypeRegistry;
use Metadata\MetadataFactory;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class DocumentManager
{
    private $identityMap;
    private $loader;
    private $typeRegistry;
    private $metadataFactory;
    private $repositoryFactory;
    private $eventDispatcher;

    public function __construct(
        AdapterInterface $adapter,
        EventDispatcherInterface $eventDispatcher = null,
        CacheItemPoolInterface $cache = null
    ) {
        $this->identityMap = new IdentityMap();
        $this->eventDispatcher = $eventDispatcher ?: new EventDispatcher();
        $this->loader = new DocumentLoader($this, $adapter, $this->eventDispatcher, $cache);
        $this->typeRegistry = new TypeRegistry();
        $this->metadataFactory = new MetadataFactory(new AnnotationDriver());
        $this->repositoryFactory = new RepositoryFactory();

    }

    public function find(string $className, string $identifier)
    {
        if ($object = $this->identityMap->getObject($className, $identifier)) {
            return $object;
        }

        $object = $this->loader->load($className, $identifier);

        $this->identityMap->add($className, $identifier, $object);

        return $object;
    }

    public function getRepository(string $class): DocumentRepository
    {
        return $this->repositoryFactory->getRepository($this, $this->loader, $class);
    }

    public function getMetadataFor($class): ClassMetadata
    {
        $metadata = $this->metadataFactory->getMetadataForClass($class);

        if (! $metadata instanceof ClassMetadata) {
            throw new DocumentMetadataException();
        }

        return $metadata;
    }

    public function getTypeRegisty(): TypeRegistry
    {
        return $this->typeRegistry;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    public function clear()
    {
        $this->identityMap = new IdentityMap();
    }
}