<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Fixture;

use DavidBadura\OrangeDb\Annotation as DB;

class ValueObject
{
    /**
     * @var string
     *
     * @DB\Type(name="string")
     */
    public $name;
}
