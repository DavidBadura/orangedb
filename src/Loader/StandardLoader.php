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
    private DocumentManager $manager;
    private AdapterInterface $adapter;
    private DocumentHydrator $hydrator;

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
            try {
                $result[$identifier] = $this->manager->find($class, $identifier);
            } catch (WrongTypeException $e) {
                // skip
            }
        }

        return $result;
    }
}
