<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb;

class PathUtil
{
    public static function join(string ...$path): string
    {
        return implode('/', $path);
    }
}
