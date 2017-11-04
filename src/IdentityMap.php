<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class IdentityMap
{
    const IDENTITY_DELIMITER = '#';

    private $objectsByIdentityMap = [];

    public function add(string $className, string $identity, $object)
    {
        $this->objectsByIdentityMap[$className . self::IDENTITY_DELIMITER . $identity] = $object;
    }

    public function getObject(string $className, string $identity)
    {
        $identityIndex = $className . self::IDENTITY_DELIMITER . $identity;

        return isset($this->objectsByIdentityMap[$identityIndex]) ? $this->objectsByIdentityMap[$identityIndex] : null;
    }
}
