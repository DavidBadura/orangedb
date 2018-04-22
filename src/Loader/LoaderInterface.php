<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Loader;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
interface LoaderInterface
{
    public function load(string $class, string $identifier): object;

    public function loadAll(string $class): array;
}
