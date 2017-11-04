<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Exception;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class FileNotFoundException extends \RuntimeException
{
    public function __construct(string $file)
    {
        parent::__construct(sprintf('File %s not found', $file));
    }
}
