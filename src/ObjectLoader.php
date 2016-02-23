<?php

namespace DavidBadura\OrangeDb;

use DavidBadura\OrangeDb\Adapter\AdapterInterface;
use DavidBadura\OrangeDb\Collection\ObjectCollection;
use DavidBadura\OrangeDb\Metadata\PropertyMetadata;
use Doctrine\Instantiator\Instantiator;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class ObjectLoader
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var Instantiator
     */
    private $instantiator;

    /**
     * @param ObjectManager $manager
     * @param AdapterInterface $adapter
     */
    public function __construct(ObjectManager $manager, AdapterInterface $adapter)
    {
        $this->manager = $manager;
        $this->adapter = $adapter;
        $this->instantiator = new Instantiator();
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

        $data = $this->adapter->load($metadata->package, $identifier);

        if ($metadata->identifier) {
            $data[$metadata->identifier] = $identifier;
        }

        return $this->hydrate($class, $data);
    }

    /**
     * @param string $class
     * @param array $data
     * @return object
     * @throws \Exception
     */
    private function hydrate($class, $data)
    {
        $object = $this->instantiator->instantiate($class);
        $metadata = $this->manager->getMetadataFor($class);

        /** @var PropertyMetadata $property */
        foreach ($metadata->propertyMetadata as $property) {
            $value = isset($data[$property->name]) ? $data[$property->name] : null;

            if ($property->type) {
                $type = $this->manager->getTypeRegisty()->get($property->type);
                $property->setValue($object, $type->transformToPhp($value));
            }

            if ($property->reference == PropertyMetadata::REFERENCE_ONE) {
                if ($value) {
                    $property->setValue($object, $this->manager->find($property->target, $value));
                }
            }

            if ($property->reference == PropertyMetadata::REFERENCE_MANY) {
                if ($value) {

                    $result = [];

                    foreach ($value as $k => $v) {
                        $result[] = $this->manager->find($property->target, $v);
                    }

                    $property->setValue($object, $result);
                }
            }

            if ($property->reference == PropertyMetadata::REFERENCE_KEY) {
                if ($value) {

                    $result = new ObjectCollection();
                    $type = $this->manager->getTypeRegisty()->get($property->value['name']);

                    foreach ($value as $k => $v) {
                        $result[$this->manager->find($property->target, $k)] = $type->transformToPhp($v);
                    }

                    $property->setValue($object, $result);
                }
            }


            if ($property->embed == PropertyMetadata::EMBED_ONE) {
                if ($value) {

                    if ($property->mapping) {
                        $value = $this->mapping($value, $property->mapping);
                    }

                    $property->setValue($object, $this->hydrate($property->target, $value));
                }
            }

            if ($property->embed == PropertyMetadata::EMBED_MANY) {
                if ($value) {

                    $result = [];

                    foreach ($value as $k => $v) {

                        if ($property->mapping) {
                            $v = $this->mapping($v, $property->mapping);
                        }

                        $result[] = $this->hydrate($property->target, $v);
                    }

                    $property->setValue($object, $result);
                }
            }
        }

        return $object;
    }

    /**
     * @param array $data
     * @param array $map
     * @return array
     */
    private function mapping($data, $map)
    {
        $result = [];

        foreach ($map as $i => $key) {
            $result[$key] = $data[$i];
        }

        return $result;
    }
}