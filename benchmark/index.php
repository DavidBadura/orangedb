<?php declare(strict_types=1);

use ACME\Character;
use DavidBadura\OrangeDb\Adapter\YamlAdapter;
use DavidBadura\OrangeDb\DocumentManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Yaml\Yaml;

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/vendor/autoload.php';

$stopwatch = new Stopwatch(true);
$numbers = 10000;
$cache = __DIR__ . '/cache';

/* Build Data */

for ($i = 1; $i <= $numbers; $i++) {
    file_put_contents(__DIR__.'/data/character/'.$i.'.yaml', Yaml::dump([
        'name' => 'Name '.$i,
        'age' => 30,
    ]));
}

/* Test without cache */

$manager = new DocumentManager(new YamlAdapter(__DIR__.'/data'));

$stopwatch->start('without cache');

for ($i = 1; $i <= $numbers; $i++) {
    $character = $manager->find(Character::class, (string)$i);
}

$stopwatch->stop('without cache');

$fs = new Filesystem();
$fs->remove($cache);
$fs->mkdir($cache);


/* Test with cache - warmup */

$manager = new DocumentManager(new YamlAdapter(__DIR__.'/data'), $cache);

$stopwatch->start('cache warmup');

for ($i = 1; $i <= $numbers; $i++) {
    $character = $manager->find(Character::class, (string)$i);
}

$stopwatch->stop('cache warmup');


/* Test with cache - run */

$manager = new DocumentManager(new YamlAdapter(__DIR__.'/data'), $cache);

$stopwatch->start('with cache');

for ($i = 1; $i <= $numbers; $i++) {
    $character = $manager->find(Character::class, (string)$i);
}

$stopwatch->stop('with cache');

/* Test instance classes */

$stopwatch->start('create objects self');

for ($i = 1; $i <= $numbers; $i++) {
    $character = new Character('Name '.$i, 30);
}

$stopwatch->stop('create objects self');

/* Test instance classes with reflaction */

$stopwatch->start('create objects with reflections');

for ($i = 1; $i <= $numbers; $i++) {
    $character = \Closure::bind(function () use ($manager, $i) {
        $self = (new ReflectionClass('ACME\Character'))->newInstanceWithoutConstructor();

        $self->name = 'Name '.$i;
        $self->age = 30;

        return $self;
    }, null, 'ACME\Character')();
}

$stopwatch->stop('create objects with reflections');


foreach ($stopwatch->getSections()['__root__']->getEvents() as $name => $event) {
    echo sprintf("%s: %s ms \n", $name, $event->getDuration());
}
