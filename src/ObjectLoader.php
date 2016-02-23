<?php

namespace DavidBadura\OrangeDb;

use DavidBadura\OrangeDb\Adapter\AdapterInterface;
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
        $this->hydrator = new Hydrator();
    }

    /**
     * @param string $class
     * @param string $identifier
     *
     * @return object
     */
    public function load($class, $identifier)
    {
        $type = $class;
        $array = $this->adapter->load($type, $identifier);

        // todo metadata
        $dateTimeType = $this->manager->getTypeRegisty()->get('datetime');
        $array['birthdate'] = $dateTimeType->transformToPhp($array['birthdate']);

        $object = $this->instantiator->instantiate($class);
        $this->hydrator->hydrate(
            $array,
            $object
        );

        return $object;
    }
}