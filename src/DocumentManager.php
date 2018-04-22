<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb;

use DavidBadura\OrangeDb\Adapter\AdapterInterface;
use DavidBadura\OrangeDb\Loader\AdapterLoader;
use DavidBadura\OrangeDb\Loader\OpcacheLoader;
use DavidBadura\OrangeDb\Metadata\ClassMetadata;
use DavidBadura\OrangeDb\Metadata\Driver\AnnotationDriver;
use DavidBadura\OrangeDb\Repository\DocumentRepository;
use DavidBadura\OrangeDb\Repository\RepositoryFactory;
use DavidBadura\OrangeDb\Type\TypeRegistry;
use Metadata\MetadataFactory;

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

    public function __construct(
        AdapterInterface $adapter,
        string $cachePath = null
    ) {
        $this->identityMap = new IdentityMap();
        $this->typeRegistry = TypeRegistry::createWithBuiltinTypes();
        $this->metadataFactory = new MetadataFactory(new AnnotationDriver());
        $this->repositoryFactory = new RepositoryFactory();

        if ($cachePath) {
            $this->loader = new OpcacheLoader($this, new AdapterLoader($this, $adapter), $cachePath);
        } else {
            $this->loader = new AdapterLoader($this, $adapter);
        }
    }

    public function find(string $className, string $identifier): object
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

        if (!$metadata instanceof ClassMetadata) {
            throw new DocumentMetadataException();
        }

        return $metadata;
    }

    public function getTypeRegisty(): TypeRegistry
    {
        return $this->typeRegistry;
    }

    public function clear(): void
    {
        $this->identityMap = new IdentityMap();
    }
}
