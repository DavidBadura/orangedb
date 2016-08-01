<?php

namespace DavidBadura\OrangeDb\Adapter;

use DavidBadura\OrangeDb\Exception\FileNotFoundException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Webmozart\PathUtil\Path;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
abstract class AbstractAdapter implements AdapterInterface
{
    /**
     * @var string
     */
    protected $directory;

    /**
     * @var string
     */
    private $extension;

    /**
     * @var array
     */
    private $cache;

    /**
     * @param string $directory
     * @param string $extension
     */
    public function __construct($directory, $extension)
    {
        $this->directory = $directory;
        $this->extension = $extension;
        $this->cache = [];
    }

    /**
     * @param string $collection
     *
     * @return string[]
     *
     * @throws \InvalidArgumentException
     */
    public function findIdentifiers($collection)
    {
        if (array_key_exists($collection, $this->cache)) {
            return $this->cache[$collection];
        }

        $dir = $this->getCollectionDirectory($collection);

        $finder = new Finder();
        $finder->in($dir)->files()->name('*.' . $this->extension);

        $identifiers = [];

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $identifiers[] = $file->getBasename('.' . $this->extension);
        }

        return $this->cache[$collection] = $identifiers;
    }

    /**
     * @param string $collection
     * @param string $identifier
     *
     * @return string
     * @throws \Exception
     */
    protected function findFile($collection, $identifier)
    {
        $file = Path::join($this->getCollectionDirectory($collection), $identifier . '.' . $this->extension);

        if (!file_exists($file)) {
            throw new FileNotFoundException($file);
        }

        return $file;
    }

    /**
     * @param string $collection
     * @return string
     */
    protected function getCollectionDirectory($collection)
    {
        return Path::join($this->directory, strtolower($collection));
    }
}