<?php

namespace DavidBadura\OrangeDb;

use GeneratedHydrator\Configuration;
use Zend\Hydrator\HydrationInterface;
use Zend\Hydrator\HydratorInterface;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class Hydrator implements HydrationInterface
{
    /**
     * @var HydratorInterface[]
     */
    private $hydratorInstances = [];

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $this->createHydrator(get_class($object))->hydrate($data, $object);
    }

    /**
     * @param string $class
     * @return HydratorInterface
     */
    private function createHydrator($class)
    {
        if (isset($this->hydratorInstances[$class])) {
            return $this->hydratorInstances[$class];
        }

        $config = new Configuration($class);
        $hydratorClass = $config->createFactory()->getHydratorClass();

        $this->hydratorInstances[$class] = new $hydratorClass();

        return $this->hydratorInstances[$class];
    }
}