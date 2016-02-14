<?php

namespace DavidBadura\OrangeDb\Adapter;

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
     * @param string $type
     * @param string $identifier
     * @param string $extension
     *
     * @return string
     *
     * @throws \Exception
     */
    public function findFile($type, $identifier, $extension)
    {
        $file = $this->directory . '/' . strtolower($type) . '/' . $identifier . '.' . $extension;

        if (!file_exists($file)) {
            throw new \Exception();
        }

        return $file;
    }
}