<?php

namespace DavidBadura\OrangeDb;

use DavidBadura\OrangeDb\Adapter\AdapterInterface;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class ObjectManager
{
    /**
     * @var IdentityMap
     */
    private $identityMap;

    /**
     * @var ObjectLoader
     */
    private $loader;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->identityMap = new IdentityMap();
        $this->loader = new ObjectLoader($adapter);
    }

    /**
     * @param string $className
     * @param string $identifier
     *
     * @return object
     */
    public function find($className, $identifier)
    {
        if ($object = $this->identityMap->getObject($className, $identifier)) {
            return $object;
        }

        $object = $this->loader->load($className, $identifier);

        $this->identityMap->add($className, $identifier, $object);

        return $object;
    }

    public function getReference($className, $id)
    {

    }
}