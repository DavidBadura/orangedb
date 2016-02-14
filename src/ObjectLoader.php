<?php

namespace DavidBadura\OrangeDb;

use DavidBadura\OrangeDb\Adapter\AdapterInterface;
use Doctrine\Instantiator\Instantiator;
use GeneratedHydrator\Configuration;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class ObjectLoader
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var Instantiator
     */
    private $instantiator;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
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
        $type = $class;
        $array = $this->adapter->load($type, $identifier);

        $hydrator = $this->createHydrator($class);
        $object = $this->instantiator->instantiate($class);

        $hydrator->hydrate(
            $array,
            $object
        );

        return $object;
    }

    /**
     * @param string $class
     * @return object
     */
    private function createHydrator($class)
    {
        $config = new Configuration($class);
        $hydratorClass = $config->createFactory()->getHydratorClass();

        return new $hydratorClass();
    }
}