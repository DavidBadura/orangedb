<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Adapter;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Webmozart\PathUtil\Path;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
abstract class AbstractAdapter implements AdapterInterface
{
    protected $directory;
    private $extension;
    private $cache;

    public function __construct(string $directory, string $extension)
    {
        $this->directory = $directory;
        $this->extension = $extension;
        $this->cache = [];
    }

    public function findIdentifiers(string $collection): array
    {
        if (array_key_exists($collection, $this->cache)) {
            return $this->cache[$collection];
        }

        $dir = $this->getCollectionDirectory($collection);

        $finder = new Finder();
        $finder->in($dir)->files()->name('*.'.$this->extension);

        $identifiers = [];

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $identifiers[] = $file->getBasename('.'.$this->extension);
        }

        return $this->cache[$collection] = $identifiers;
    }

    protected function findFile(string $collection, string $identifier): string
    {
        $file = Path::join($this->getCollectionDirectory($collection), $identifier.'.'.$this->extension);

        if (!file_exists($file)) {
            throw new FileNotFoundException($file);
        }

        return $file;
    }

    protected function getCollectionDirectory(string $collection): string
    {
        return Path::join($this->directory, strtolower($collection));
    }
}
