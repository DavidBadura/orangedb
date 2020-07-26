OrangeDb
========

OrangeDB is a library about high performance transition of master data from xml to objects.

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
     * @OD\XmlName("child")
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

character/sarah.xml: 

```xml
<?xml ?>
<character>
    <name>Sarah Connor</name>
    <age>32</age>
    <child>john</child>
</character>
```

character/john.xml: 

```xml
<?xml ?>
<character>
    <name>John Connor</name>
    <age>8</age>
</character>
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
