<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Fixture;

use DavidBadura\OrangeDb\Annotation as DB;

/**
 * @DB\Document(collection="trait")
 */
class TraitFixture
{
    use UseMe;

    /**
     * @DB\Type(name="string")
     */
    public $name;
}
