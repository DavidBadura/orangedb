<?php

namespace DavidBadura\OrangeDb\Exception;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class FileNotFoundException extends \RuntimeException
{
    /**
     * @param string $file
     */
    public function __construct($file)
    {
        parent::__construct(sprintf('File %s not found', $file));
    }
}