<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Loader;

use DavidBadura\OrangeDb\Adapter\AdapterInterface;
use DavidBadura\OrangeDb\DocumentHydrator;
use DavidBadura\OrangeDb\DocumentManager;
use DavidBadura\OrangeDb\DocumentMetadataException;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class StandardLoader implements LoaderInterface
{
    private $manager;
    private $adapter;
    private $hydrator;

    public function __construct(
        DocumentManager $manager,
        AdapterInterface $adapter
    ) {
        $this->manager = $manager;
        $this->adapter = $adapter;
        $this->hydrator = new DocumentHydrator($this->manager);
    }

    public function load(string $class, string $identifier): object
    {
        $metadata = $this->manager->getMetadataFor($class);

        if (!$metadata->collection) {
            throw new DocumentMetadataException(sprintf('"%s" is not a document', $class));
        }

        $data = $this->adapter->load($metadata->collection, $identifier);

        if ($metadata->identifier) {
            $data[$metadata->identifier] = $identifier;
        }

        if ($metadata->discriminatorColumn) {
            $type = $data[$metadata->discriminatorColumn];
            $class = $metadata->discriminatorMap[$type];
            unset($data[$metadata->discriminatorColumn]);
        }

        return $this->hydrator->hydrate($class, $data);
    }

    public function loadAll(string $class): array
    {
        $metadata = $this->manager->getMetadataFor($class);

        if (!$metadata->collection) {
            throw new DocumentMetadataException('not a document');
        }

        $identifiers = $this->adapter->findIdentifiers($metadata->collection);
        $result = [];

        foreach ($identifiers as $identifier) {
            $object = $this->manager->find($class, $identifier);

            if (is_a($object, $class)) {
                $result[$identifier] = $this->manager->find($class, $identifier);
            }
        }

        return $result;
    }
}
