<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Loader;

use DavidBadura\OrangeDb\DocumentManager;
use DavidBadura\OrangeDb\Generator\Generator;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class PhpCachedLoader implements LoaderInterface
{
    private LoaderInterface $loader;
    private DocumentManager $manager;
    private string $path;
    private Generator $generator;

    public function __construct(
        LoaderInterface $loader,
        DocumentManager $documentManager,
        string $path
    ) {
        $this->loader = $loader;
        $this->manager = $documentManager;
        $this->path = $path;
        $this->generator = new Generator($documentManager);
    }

    public function load(string $class, string $identifier): object
    {
        $path = $this->entityPath($class, $identifier);

        if (file_exists($path)) {
            $manager = $this->manager;

            return require $path;
        }

        $object = $this->loader->load($class, $identifier);

        $this->dump($path, $object);

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

        $this->dumpIndex($path, $class, array_keys($result));

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

    private function dump(string $path, object $object): void
    {
        $content = "<?php\n\n";
        $content .= 'return '.$this->generator->generate($object).";\n";

        $this->write($path, $content);
    }

    private function dumpIndex(string $path, string $class, array $identifiers): void
    {
        $content = "<?php\n\n";
        $content .= "return [\n";

        foreach ($identifiers as $id) {
            $content .= "    '$id' => \$manager->find('$class', '$id'),\n";
        }

        $content .= '];';

        $this->write($path, $content);
    }

    private function write(string $path, string $content): void
    {
        $dir = dirname($path);

        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
            }
        }

        file_put_contents($path, $content);
    }
}
