<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class DateTimeType implements TypeInterface
{
    public function transformToPhp($value, array $options): \DateTime
    {
        return new \DateTime((string)$value);
    }

    public function transformToDump($value, array $options): string
    {
        if ($value === null) {
            return 'null';
        }

        $string = $value->format(\DateTime::ATOM);

        return "new \DateTime('$string')";
    }

    public function getName(): string
    {
        return 'datetime';
    }
}
