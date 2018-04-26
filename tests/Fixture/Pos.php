<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Fixture;

use DavidBadura\OrangeDb\Annotation as DB;

class Pos
{
    /**
     * @var int
     *
     * @DB\Type(name="int")
     */
    public $x;

    /**
     * @var int
     *
     * @DB\Type(name="int")
     */
    public $y;
}
