<?php

namespace Model;

use DavidBadura\OrangeDb\Annotation as DB;

class Material
{
    /**
     * @var int
     *
     * @DB\Id()
     * @DB\Type(type="int")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Type(type="string")
     */
    protected $name;

    /**
     * @var bool
     *
     * @ORM\Type(type="bool")
     */
    protected $food;

    /**
     *
     */
    public function __construct()
    {
        $this->food = false;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isFood()
    {
        return $this->food;
    }
}