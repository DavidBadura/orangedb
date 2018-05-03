<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Fixture;

use DavidBadura\OrangeDb\Annotation\Type;

class InheritanceCallbackBar extends InheritanceCallbackBase
{
    /**
     * @Type(name="string")
     */
    public $baz;
}
