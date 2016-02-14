<?php

require_once __DIR__ . '/../vendor/autoload.php';

use DavidBadura\OrangeDb\ObjectManager;
use DavidBadura\OrangeDb\Adapter\YamlAdapter;

$objectManager = new ObjectManager(new YamlAdapter(__DIR__ . '/data'));
$user = $objectManager->find(User::class, 'john');

var_dump($user);

class User
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $age;

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
}