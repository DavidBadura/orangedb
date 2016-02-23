<?php

namespace Model;

use DavidBadura\OrangeDb\Annotation as DB;

/**
 * @DB\Entity(package="building")
 */
class Building
{
    /**
     * @var int
     *
     * @DB\Id()
     * @DB\Type(name="string")
     */
    protected $id;

    /**
     * @var string
     *
     * @DB\Type(name="string")
     */
    protected $name;

    /**
     * @var int
     * @DB\Type(name="int")
     */
    protected $constructionRounds;

    /**
     * @var array
     * @DB\ReferenceKey(target="Model\Material", value=@DB\Type(name="int"))
     */
    protected $constructionCosts;

    /**
     *
     */
    public function __construct()
    {
        $this->constructionRounds = 1;
        $this->constructionCosts = [];
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
     * @return int
     */
    public function getConstructionRounds()
    {
        return $this->constructionRounds;
    }

    /**
     * @return array
     */
    public function getConstructionCosts()
    {
        return $this->constructionCosts;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}