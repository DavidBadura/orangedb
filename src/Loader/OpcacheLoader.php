<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Loader;

use DavidBadura\OrangeDb\DocumentManager;
use DavidBadura\OrangeDb\Dumper\Dumper;
use Doctrine\Instantiator\Instantiator;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class OpcacheLoader implements LoaderInterface
{
    private $manager;
    private $loader;
    private $path;
    private $dumper;

    public function __construct(
        DocumentManager $documentManager,
        LoaderInterface $loader,
        string $path
    ) {
        $this->manager = $documentManager;
        $this->loader = $loader;
        $this->path = $path;
        $this->dumper = new Dumper($documentManager);
    }

    public function load(string $class, string $identifier): object
    {
        $path = $this->path($class, $identifier);

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
        // not supported yet
        return $this->loader->loadAll($class);
    }

    private function path(string $class, string $identifier): string
    {
        return $this->path.'/'.str_replace('\\', '/', $class).'/'.$identifier.'.php';
    }
}
