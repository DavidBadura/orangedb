<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
interface TypeInterface
{
    public function transformToPhp($value);
    public function transformToDump($value): string;
    public function getName(): string;
}
