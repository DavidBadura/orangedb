<?php

require_once __DIR__ . '/../vendor/autoload.php';

use DavidBadura\OrangeDb\Adapter\YamlAdapter;
use DavidBadura\OrangeDb\Annotation as DB;
use DavidBadura\OrangeDb\DocumentManager;


class UserRepository extends \DavidBadura\OrangeDb\Repository\DocumentRepository
{

}

$manager = new DocumentManager(new YamlAdapter(__DIR__ . '/data'));
$user = $manager->getRepository(User::class)->find('john');


dump($user);
dump($manager->getRepository(User::class));




/**
 * @DB\Document(repository="UserRepository")
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
     * @DB\Type(name="int")
     */
    private $age;

    /**
     * @DB\Type(name="datetime")
     */
    private $birthdate;

    /**
     * @var User
     *
     * @DB\ReferenceOne(target="User")
     */
    private $parent;

    /**
     * @var User[]
     *
     * @DB\ReferenceMany(target="User")
     */
    private $children = [];

    /**
     * @var Pos
     *
     * @DB\EmbedOne(target="Pos", mapping={"x", "y"})
     */
    private $pos;

    /**
     * @var Pos
     *
     * @DB\EmbedOne(target="Pos")
     */
    private $pos2;

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

class Pos
{
    /**
     * @var int
     *
     * @DB\Type(name="int")
     */
    public $x;

    /**
     * @var int
     *
     * @DB\Type(name="int")
     */
    public $y;
}
