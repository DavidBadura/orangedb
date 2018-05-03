<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Fixture;

use DavidBadura\OrangeDb\Annotation\Type;

class InheritanceCallbackFoo extends InheritanceCallbackBase
{
    /**
     * @Type(name="int")
     */
    public $age;
}
