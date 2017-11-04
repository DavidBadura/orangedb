<?php

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
    /**
     * @var DocumentManager
     */
    private $manager;

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var DocumentHydrator
     */
    private $hydrator;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param DocumentManager $manager
     * @param AdapterInterface $adapter
     * @param EventDispatcherInterface $eventDispatcher
     * @param CacheItemPoolInterface|null $cache
     */
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

    /**
     * @param string $class
     * @param string $identifier
     *
     * @return object
     */
    public function load($class, $identifier)
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

    /**
     * @param string $class
     * @return object[]
     */
    public function loadAll($class)
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

    /**
     * @param string $class
     * @param array $data
     * @return object
     */
    private function hydrate($class, $data)
    {
        $object = $this->hydrator->hydrate($class, $data);

        $metadata = $this->manager->getMetadataFor($class);

        $event = new DocumentEvent($this->manager, $metadata, $object);
        $this->eventDispatcher->dispatch(Events::POST_LOAD, $event);

        return $object;
    }
}