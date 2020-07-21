<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb;

use DavidBadura\OrangeDb\Adapter\AdapterInterface;
use DavidBadura\OrangeDb\Loader\LoaderInterface;
use DavidBadura\OrangeDb\Loader\OnePhpCachedLoader;
use DavidBadura\OrangeDb\Loader\PhpCachedLoader;
use DavidBadura\OrangeDb\Loader\StandardLoader;
use DavidBadura\OrangeDb\Metadata\ClassMetadata;
use DavidBadura\OrangeDb\Metadata\Driver\AnnotationDriver;
use DavidBadura\OrangeDb\Repository\DocumentRepository;
use DavidBadura\OrangeDb\Repository\RepositoryFactory;
use DavidBadura\OrangeDb\Type\TypeInterface;
use DavidBadura\OrangeDb\Type\TypeRegistry;
use Metadata\MetadataFactory;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class DocumentManager
{
    private IdentityMap $identityMap;
    private LoaderInterface $loader;
    private TypeRegistry $typeRegistry;
    private MetadataFactory $metadataFactory;
    private RepositoryFactory $repositoryFactory;

    public function __construct(
        AdapterInterface $adapter,
        string $cachePath = null
    ) {
        $this->identityMap = new IdentityMap();
        $this->typeRegistry = TypeRegistry::createWithBuiltinTypes();
        $this->metadataFactory = new MetadataFactory(new AnnotationDriver());
        $this->repositoryFactory = new RepositoryFactory();
        $this->loader = new StandardLoader($this, $adapter);

        if ($cachePath) {
            $this->loader = new PhpCachedLoader($this->loader, $this, $cachePath);
            //$this->loader = new OnePhpCachedLoader($adapter, $this, $cachePath);
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

    public function getMetadataFor(string $class): ClassMetadata
    {
        if (!class_exists($class)) {
            throw new DocumentMetadataException(sprintf('"%s" not found', $class));
        }

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

    public function addType(TypeInterface $type): void
    {
        $this->typeRegistry->addType($type);
    }

    public function clear(): void
    {
        $this->identityMap = new IdentityMap();
    }
}
