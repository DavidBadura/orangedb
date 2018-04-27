<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Fixture;

use DavidBadura\OrangeDb\Annotation as DB;

/**
 * @DB\Document()
 */
class Middle extends Child
{
    /**
     * @DB\Type(name="int")
     */
    protected $test;
}
