<?php

namespace Model;

use DavidBadura\OrangeDb\Annotation as DB;

/**
 * @DB\Document(collection="material")
 */
class Material
{
    /**
     * @var int
     *
     * @DB\Id()
     * @DB\Type(name="int")
     */
    protected $id;

    /**
     * @var string
     *
     * @DB\Type(name="string")
     */
    protected $name;

    /**
     * @var bool
     *
     * @DB\Type(name="bool")
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