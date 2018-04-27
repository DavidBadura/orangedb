<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Fixture;

use DavidBadura\OrangeDb\Annotation\Type;

class InheritanceFoo extends InheritanceBase
{
    /**
     * @Type(name="int")
     */
    public $age;
}