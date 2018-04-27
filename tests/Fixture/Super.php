<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Fixture;

use DavidBadura\OrangeDb\Annotation as DB;

/**
 * @DB\Document()
 */
class Super extends Middle
{
    /**
     * @DB\Type(name="int")
     */
    public $age;


    public function getTest(): int
    {
        return $this->test;
    }
}
