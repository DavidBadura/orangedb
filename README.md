OrangeDb
========

OrangeDB is a library about high performance transition of master data from yaml/json to objects.

[![Build Status](https://travis-ci.org/DavidBadura/orangedb.svg?branch=master)](https://travis-ci.org/DavidBadura/orangedb)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/DavidBadura/orangedb/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/DavidBadura/orangedb/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/DavidBadura/orangedb/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/DavidBadura/orangedb/?branch=master)


Installation
------------

You can easily install this package over composer

```
composer require 'davidbadura/orangedb'
```

Example
-------

### Model

```php
<?php

use DavidBadura\OrangeDb\Annotation as OD;

/**
 * @OD\Document("character")
 */
class Character {
    
    /**
     * @OD\Type("string")
     */
    private $name;
    
    /**
     * @OD\Type("integer")
     */
    private $age;
    
    /**
     * @OD\ReferenceMany("Character")
     */
    private $children;
    
    public function getName(): string 
    {
        return $this->name;
    }
    
    public function getAge(): int 
    {
        return $this->age;
    }
    
    public function getChildren(): array 
    {
        return $this->children;
    }
}
```

### Data

```yaml
# /var/cache/orangedb/character/sarah.yaml

name: Sarah Connor
age: 32
children: ['john']

# /var/cache/orangedb/character/john.yaml

name: John Connor
age: 8
children: []
```

### Usage

```php
<?php

use DavidBadura\OrangeDb\DocumentManager;
use DavidBadura\OrangeDb\Adapter\YamlAdapter;

$manager = new DocumentManager(new YamlAdapter(__DIR__.'/data'), __DIR__.'/var/cache/orangedb');

$character = $manager->find(Character::class, 'sarah');

echo $character->getName(); // Sarah Connor
echo $character->getAge(); // 32

echo count($character->getChildren()); // 1
echo $character->getChildren()[0]->getName(); //John Connor
```