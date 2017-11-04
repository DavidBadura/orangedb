<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb;

use DavidBadura\OrangeDb\Adapter\AdapterInterface;
use DavidBadura\OrangeDb\Event\DocumentEvent;
use DavidBadura\OrangeDb\Exception\DocumentMetadataException;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class DocumentLoader
{
    private $manager;
    private $adapter;
    private $eventDispatcher;
    private $hydrator;
    private $cache;

    public function __construct(
        DocumentManager $manager,
        AdapterInterface $adapter,
        EventDispatcherInterface $eventDispatcher,
        CacheItemPoolInterface $cache = null
    ) {
        $this->manager = $manager;
        $this->adapter = $adapter;
        $this->eventDispatcher = $eventDispatcher;
        $this->cache = $cache;
        $this->hydrator = new DocumentHydrator($this->manager);
    }

    public function load(string $class, string $identifier)
    {
        $metadata = $this->manager->getMetadataFor($class);

        if (!$metadata->collection) {
            throw new DocumentMetadataException('not a document');
        }

        $cacheItem = null;

        if ($this->cache) {
            $cacheKey = $metadata->collection.'.'.$identifier;
            $cacheItem = $this->cache->getItem($cacheKey);

            if ($cacheItem->isHit()) {
                return $this->hydrate($class, $cacheItem->get());
            }
        }

        $data = $this->adapter->load($metadata->collection, $identifier);

        if ($metadata->identifier) {
            $data[$metadata->identifier] = $identifier;
        }

        if ($cacheItem) {
            $cacheItem->set($data);
            $this->cache->save($cacheItem);
        }

        return $this->hydrate($class, $data);
    }

    public function loadAll(string $class): array
    {
        $metadata = $this->manager->getMetadataFor($class);

        if (!$metadata->collection) {
            throw new DocumentMetadataException('not a document');
        }

        $identifiers = null;
        $cacheItem = null;

        if ($this->cache) {
            $cacheItem = $this->cache->getItem($metadata->collection);

            if ($cacheItem->isHit()) {
                $identifiers = $cacheItem->get();
            }
        }

        if (!$identifiers) {
            $identifiers = $this->adapter->findIdentifiers($metadata->collection);
            $cacheItem->set($identifiers);
            $this->cache->save($cacheItem);
        }

        $result = [];

        foreach ($identifiers as $identifier) {
            $result[] = $this->manager->find($class, $identifier);
        }

        return $result;
    }

    private function hydrate(string $class, array $data)
    {
        $object = $this->hydrator->hydrate($class, $data);

        $metadata = $this->manager->getMetadataFor($class);

        $event = new DocumentEvent($this->manager, $metadata, $object);
        $this->eventDispatcher->dispatch(Events::POST_LOAD, $event);

        return $object;
    }
}
