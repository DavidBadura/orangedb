<?php

require_once __DIR__ . '/../vendor/autoload.php';

use DavidBadura\OrangeDb\Adapter\YamlAdapter;
use DavidBadura\OrangeDb\Annotation as DB;
use DavidBadura\OrangeDb\ObjectManager;

$objectManager = new ObjectManager(new YamlAdapter(__DIR__ . '/data'));
$user = $objectManager->find(User::class, 'john');

var_dump($user);

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
}