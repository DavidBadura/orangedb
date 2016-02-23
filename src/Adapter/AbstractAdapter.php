<?php

namespace DavidBadura\OrangeDb\Adapter;

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
     * @param string $directory
     */
    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @param string $collection
     * @param string $identifier
     * @param string $extension
     *
     * @return string
     *
     * @throws \Exception
     */
    public function findFile($collection, $identifier, $extension)
    {
        $file = Path::join($this->directory, strtolower($collection), $identifier . '.' . $extension);

        if (!file_exists($file)) {
            throw new \Exception();
        }

        return $file;
    }
}