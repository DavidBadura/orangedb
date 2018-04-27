<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Fixture;

use DavidBadura\OrangeDb\Annotation as DB;

abstract class Child
{
    /**
     * @DB\Id()
     * @DB\Type(name="string")
     */
    protected $id;

    /**
     * @DB\Type(name="string")
     */
    protected $name;

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
