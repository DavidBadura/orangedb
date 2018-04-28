<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class NotFoundException extends \RuntimeException
{
    public function __construct(string $identifier, string $collection, \Throwable $exception = null)
    {
        parent::__construct(sprintf('%s in %s not found', $identifier, $collection), 0, $exception);
    }
}
