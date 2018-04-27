<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Loader;

use DavidBadura\OrangeDb\DocumentManager;
use DavidBadura\OrangeDb\Dumper\Dumper;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class PhpCachedLoader implements LoaderInterface
{
    private $manager;
    private $path;
    private $dumper;
    private $loader;

    public function __construct(
        StandardLoader $loader,
        DocumentManager $documentManager,
        string $path
    ) {
        $this->loader = $loader;
        $this->manager = $documentManager;
        $this->path = $path;
        $this->dumper = new Dumper($documentManager);
    }

    public function load(string $class, string $identifier): object
    {
        $path = $this->entityPath($class, $identifier);

        if (file_exists($path)) {
            $manager = $this->manager;

            return require $path;
        }

        $object = $this->loader->load($class, $identifier);

        $this->dumper->dump($path, $object);

        return $object;
    }

    public function loadAll(string $class): array
    {
        $path = $this->indexPath($class);

        if (file_exists($path)) {
            $manager = $this->manager;

            return require $path;
        }

        $result = $this->loader->loadAll($class);

        $this->dumper->dumpIndex($path, $class, array_keys($result));

        return $result;
    }

    private function entityPath(string $class, string $identifier): string
    {
        return $this->path.'/'.str_replace('\\', '/', $class).'/'.$identifier.'.php';
    }

    private function indexPath(string $class): string
    {
        return $this->path.'/'.str_replace('\\', '/', $class).'/_index.php';
    }
}
