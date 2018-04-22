<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Fixture;

use DavidBadura\OrangeDb\Annotation as DB;

/**
 * @DB\Document(collection="building")
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
     *
     */
    public function __construct()
    {
        $this->constructionRounds = 1;
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
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
