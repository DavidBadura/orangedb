<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Fixture;

use DavidBadura\OrangeDb\Annotation\Document;
use DavidBadura\OrangeDb\Annotation\EmbedOne;

/**
 * @Document("embedded_inheritance")
 */
class EmbeddedInheritance
{
    /**
     * @EmbedOne("DavidBadura\OrangeDb\Test\Fixture\InheritanceBase")
     */
    public $test;
}
