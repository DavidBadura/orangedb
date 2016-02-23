<?php

namespace DavidBadura\OrangeDb;

use DavidBadura\OrangeDb\Adapter\AdapterInterface;
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
        $array = $this->adapter->load($class, $identifier);
        $object = $this->instantiator->instantiate($class);
        $metadata = $this->manager->getMetadataFor($class);

        $array[$metadata->identifier] = $identifier;

        /** @var PropertyMetadata $property */
        foreach ($metadata->propertyMetadata as $property) {
            $type = $this->manager->getTypeRegisty()->get($property->type);
            $property->setValue($object, $type->transformToPhp($array[$property->name]));
        }

        return $object;
    }
}