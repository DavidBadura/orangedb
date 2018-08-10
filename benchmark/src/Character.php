<?php

namespace ACME;

use DavidBadura\OrangeDb\Annotation as OD;

/**
 * @OD\Document("character")
 */
class Character {

    /**
     * @OD\Type("string")
     */
    private $name;

    /**
     * @OD\Type("int")
     */
    private $age;

    public function __construct(string $name, int $age)
    {
        $this->name;
        $this->age;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAge(): int
    {
        return $this->age;
    }
}