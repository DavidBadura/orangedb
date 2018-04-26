<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Fixture;

use DavidBadura\OrangeDb\Annotation as DB;

/**
 * @DB\Document()
 */
class Material
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

    /**
     * @DB\Type(name="bool")
     */
    protected $food;

    public function __construct()
    {
        $this->food = false;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isFood(): bool
    {
        return $this->food;
    }
}
