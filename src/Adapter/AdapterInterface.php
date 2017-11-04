<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Adapter;

/**
 * @author David Badura <d.badura@gmx.de>
 */
interface AdapterInterface
{
    /**
     * @return object[]
     */
    public function load(string $collection, string $identifier): array;

    /**
     * @return string[]
     */
    public function findIdentifiers(string $collection): array;
}
