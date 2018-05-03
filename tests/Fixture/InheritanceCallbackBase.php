<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Fixture;

use DavidBadura\OrangeDb\Annotation\DiscriminatorColumn;
use DavidBadura\OrangeDb\Annotation\DiscriminatorMap;
use DavidBadura\OrangeDb\Annotation\Document;
use DavidBadura\OrangeDb\Annotation\Type;

/**
 * @Document(collection="inheritance")
 * @DiscriminatorColumn(name="type")
 * @DiscriminatorMap(callback="classMap")
 */
abstract class InheritanceCallbackBase
{
    /**
     * @Type(name="string")
     */
    public $name;

    public static function classMap(string $type): string
    {
        $map = [
            'foo' => InheritanceCallbackFoo::class,
            'bar' => InheritanceCallbackBar::class,
        ];

        return $map[$type];
    }
}
