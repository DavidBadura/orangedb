<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Fixture;

use DavidBadura\OrangeDb\Annotation as DB;

/**
 * @DB\Document(repository="DavidBadura\OrangeDb\Test\Fixture\UserRepository")
 */
class User
{
    /**
     * @var string
     *
     * @DB\Id
     * @DB\Type(name="string")
     */
    private $id;

    /**
     * @var string
     *
     * @DB\Type(name="string")
     */
    private $name;

    /**
     * @var int
     *
     * @DB\Type(name="int", nullable=true)
     */
    private $age;

    /**
     * @DB\Type(name="datetime", nullable=true)
     */
    private $birthdate;

    /**
     * @var User
     *
     * @DB\ReferenceOne(target="DavidBadura\OrangeDb\Test\Fixture\User")
     */
    private $parent;

    /**
     * @var User[]
     *
     * @DB\ReferenceMany(target="DavidBadura\OrangeDb\Test\Fixture\User")
     */
    private $children = [];

    /**
     * @var Pos
     *
     * @DB\EmbedOne(target="DavidBadura\OrangeDb\Test\Fixture\Pos", mapping={"x", "y"})
     */
    private $pos;

    /**
     * @var Pos
     *
     * @DB\EmbedOne(target="DavidBadura\OrangeDb\Test\Fixture\Pos")
     */
    private $pos2;


    /**
     * @var Pos
     *
     * @DB\EmbedMany(target="DavidBadura\OrangeDb\Test\Fixture\Pos", mapping={"x", "y"})
     */
    private $positions;

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
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @return User
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return User[]
     */
    public function getChildren()
    {
        return $this->children;
    }
}