<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/Material.php';
require_once __DIR__ . '/Building.php';

use DavidBadura\OrangeDb\Adapter\YamlAdapter;
use DavidBadura\OrangeDb\DocumentManager;
use Model\Building;

$manager = new DocumentManager(new YamlAdapter(__DIR__ . '/data'));

/** @var Building $house */
$house = $manager->getRepository(Building::class)->find('house');

dump($house);

foreach ($house->getConstructionCosts() as $material => $value) {
    dump($material);
    dump($value);
}