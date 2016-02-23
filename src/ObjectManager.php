<?php

namespace DavidBadura\OrangeDb;

use DavidBadura\OrangeDb\Adapter\AdapterInterface;
use DavidBadura\OrangeDb\Type\TypeRegistry;

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
     * @var TypeRegistry
     */
    private $typeRegistry;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->identityMap = new IdentityMap();
        $this->loader = new ObjectLoader($this, $adapter);
        $this->typeRegistry = new TypeRegistry();
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

    /**
     * @return TypeRegistry
     */
    public function getTypeRegisty()
    {
        return $this->typeRegistry;
    }
}