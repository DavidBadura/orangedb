<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Loader;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class WrongTypeException extends \RuntimeException
{
    public function __construct(string $requestedClass, string $discriminatorClass)
    {
        parent::__construct(
            sprintf(
                'Requested class %s is not instanceof %s. Requested class and discriminator value mismatch?',
                $requestedClass,
                $discriminatorClass
            )
        );
    }
}
