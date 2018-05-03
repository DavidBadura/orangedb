<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Fixture;

use DavidBadura\OrangeDb\Annotation as DB;

/**
 * @DB\Document("value_object")
 */
class ValueObjectOwner
{
    /**
     * @var ValueObject
     *
     * @DB\EmbedOne("DavidBadura\OrangeDb\Test\Fixture\ValueObject", mapping="name")
     */
    public $type;
}
