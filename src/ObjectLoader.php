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

        $object = $this->instantiator->instantiate($class);
        $this->hydrator->hydrate(
            $array,
            $object
        );

        return $object;
    }
}