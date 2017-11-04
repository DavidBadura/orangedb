<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class DateTimeType implements TypeInterface
{
    public function transformToPhp($value): \DateTime
    {
        return new \DateTime($value);
    }

    public function getName(): string
    {
        return 'datetime';
    }
}
