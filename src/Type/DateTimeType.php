<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class DateTimeType implements TypeInterface
{
    public function transformToPhp($value): \DateTime
    {
        return new \DateTime((string)$value);
    }

    public function transformToDump($value): string
    {
        $string = $value->format(\DateTime::ISO8601);

        return "new \DateTime('$string')";
    }

    public function getName(): string
    {
        return 'datetime';
    }
}
