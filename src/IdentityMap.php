<?php

namespace DavidBadura\OrangeDb;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class IdentityMap
{
    const IDENTITY_DELIMITER = '#';

    /**
     * @var object[]
     */
    private $objectsByIdentityMap = [];

    /**
     * @param string $className
     * @param string $identity
     * @param object $object
     */
    public function add($className, $identity, $object)
    {
        $this->objectsByIdentityMap[$className . self::IDENTITY_DELIMITER . $identity] = $object;
    }

    /**
     * @param string $className
     * @param string $identity
     *
     * @return object|null
     */
    public function getObject($className, $identity)
    {
        $identityIndex = $className . self::IDENTITY_DELIMITER . $identity;
        return isset($this->objectsByIdentityMap[$identityIndex]) ? $this->objectsByIdentityMap[$identityIndex] : null;
    }
}