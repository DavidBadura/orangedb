<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Fixture;

use DavidBadura\OrangeDb\Annotation\DiscriminatorColumn;
use DavidBadura\OrangeDb\Annotation\DiscriminatorMap;
use DavidBadura\OrangeDb\Annotation\Document;
use DavidBadura\OrangeDb\Annotation\Type;

/**
 * @Document(collection="inheritance")
 * @DiscriminatorColumn(name="type")
 * @DiscriminatorMap({"foo": "DavidBadura\OrangeDb\Test\Fixture\InheritanceFoo", "bar": "DavidBadura\OrangeDb\Test\Fixture\InheritanceBar"})
 */
abstract class InheritanceBase
{
    /**
     * @Type(name="string")
     */
    public $name;
}
