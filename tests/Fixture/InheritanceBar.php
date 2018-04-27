<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Fixture;

use DavidBadura\OrangeDb\Annotation\Type;

class InheritanceBar extends InheritanceBase
{
    /**
     * @Type(name="string")
     */
    public $baz;
}