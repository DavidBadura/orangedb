<?php

namespace DavidBadura\OrangeDb;

use DavidBadura\OrangeDb\Adapter\AdapterInterface;
use DavidBadura\OrangeDb\Metadata\ClassMetadata;
use DavidBadura\OrangeDb\Metadata\Driver\AnnotationDriver;
use DavidBadura\OrangeDb\Repository\RepositoryFactory;
use DavidBadura\OrangeDb\Type\TypeRegistry;
use Metadata\MetadataFactory;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class DocumentManager
{
    /**
     * @var IdentityMap
     */
    private $identityMap;

    /**
     * @var DocumentLoader
     */
    private $loader;

    /**
     * @var TypeRegistry
     */
    private $typeRegistry;

    /**
     * @var MetadataFactory
     */
    private $metadataFactory;

    /**
     * @var RepositoryFactory
     */
    private $repositoryFactory;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->identityMap = new IdentityMap();
        $this->loader = new DocumentLoader($this, $adapter);
        $this->typeRegistry = new TypeRegistry();
        $this->metadataFactory = new MetadataFactory(new AnnotationDriver());
        $this->repositoryFactory = new RepositoryFactory();
    }

    /**
     * @param string $className
     * @param string $identifier
     *
     * @return object
     */
    public function find($className, $identifier)
    {
        if ($object = $this->identityMap->getObject($className, $identifier)) {
            return $object;
        }

        $object = $this->loader->load($className, $identifier);

        $this->identityMap->add($className, $identifier, $object);

        return $object;
    }

    /**
     * @param string $class
     * @return Repository\DocumentRepository
     */
    public function getRepository($class)
    {
        return $this->repositoryFactory->getRepository($this, $class);
    }

    /**
     * @param string $class
     * @return ClassMetadata|null
     */
    public function getMetadataFor($class)
    {
        return $this->metadataFactory->getMetadataForClass($class);
    }

    /**
     * @return TypeRegistry
     */
    public function getTypeRegisty()
    {
        return $this->typeRegistry;
    }
}