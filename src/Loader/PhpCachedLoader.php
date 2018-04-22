<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Loader;

use DavidBadura\OrangeDb\Adapter\AdapterInterface;
use DavidBadura\OrangeDb\DocumentHydrator;
use DavidBadura\OrangeDb\DocumentManager;
use DavidBadura\OrangeDb\DocumentMetadataException;
use DavidBadura\OrangeDb\Dumper\Dumper;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class PhpCachedLoader implements LoaderInterface
{
    private $manager;
    private $adapter;
    private $path;
    private $dumper;
    private $hydrator;

    public function __construct(
        DocumentManager $documentManager,
        AdapterInterface $adapter,
        string $path
    ) {
        $this->manager = $documentManager;
        $this->adapter = $adapter;
        $this->path = $path;
        $this->dumper = new Dumper($documentManager);
        $this->hydrator = new DocumentHydrator($this->manager);
    }

    public function load(string $class, string $identifier): object
    {
        $path = $this->entityPath($class, $identifier);

        if (file_exists($path)) {
            $manager = $this->manager;

            return require $path;
        }

        $metadata = $this->manager->getMetadataFor($class);

        if (!$metadata->collection) {
            throw new DocumentMetadataException('not a document');
        }

        $data = $this->adapter->load($metadata->collection, $identifier);

        if ($metadata->identifier) {
            $data[$metadata->identifier] = $identifier;
        }

        $object = $this->hydrator->hydrate($class, $data);

        $this->dumper->dump($path, $object);

        return $object;
    }

    public function loadAll(string $class): array
    {
        $path = $this->indexPath($class);

        if (file_exists($path)) {
            $manager = $this->manager;

            return require $path;
        }

        $metadata = $this->manager->getMetadataFor($class);

        if (!$metadata->collection) {
            throw new DocumentMetadataException('not a document');
        }

        $identifiers = $this->adapter->findIdentifiers($metadata->collection);
        $result = [];

        foreach ($identifiers as $identifier) {
            $result[] = $this->manager->find($class, $identifier);
        }

        $this->dumper->dumpIndex($path, $class, $identifiers);

        return $result;
    }

    private function entityPath(string $class, string $identifier): string
    {
        return $this->path.'/'.str_replace('\\', '/', $class).'/'.$identifier.'.php';
    }

    private function indexPath(string $class): string
    {
        return $this->path.'/'.str_replace('\\', '/', $class).'/_index.php';
    }
}
