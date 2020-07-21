<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class IdentityMap
{
    private const IDENTITY_DELIMITER = '#';

    private array $objectsByIdentityMap = [];

    public function add(string $className, string $identity, object $object): void
    {
        $this->objectsByIdentityMap[$className . self::IDENTITY_DELIMITER . $identity] = $object;
    }

    public function getObject(string $className, string $identity): ?object
    {
        $identityIndex = $className . self::IDENTITY_DELIMITER . $identity;

        return isset($this->objectsByIdentityMap[$identityIndex]) ? $this->objectsByIdentityMap[$identityIndex] : null;
    }
}
