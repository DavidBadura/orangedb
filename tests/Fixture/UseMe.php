<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Fixture;

use DavidBadura\OrangeDb\Annotation as DB;

trait UseMe
{
    /**
     * @DB\Type(name="int")
     */
    public $age;
}
