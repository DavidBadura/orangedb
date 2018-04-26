<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Adapter;

use DavidBadura\OrangeDb\Adapter\AdapterInterface;
use DavidBadura\OrangeDb\Adapter\FileNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
abstract class AbstractAdapterTest extends TestCase
{
    abstract public function createAdapter(): AdapterInterface;

    public function testLoad()
    {
        $document = $this->createAdapter()->load('building', 'house');

        $this->assertEquals('House', $document['name']);
    }

    public function testNotFound()
    {
        $this->expectException(FileNotFoundException::class);

        $this->createAdapter()->load('building', 'xxx');
    }

    public function testFindIdentifieres()
    {
        $identifiers = $this->createAdapter()->findIdentifiers('building');

        $this->assertCount(1, $identifiers);
        $this->assertContains('house', $identifiers);
    }

    public function testLoadFromCache()
    {
        $adapter = $this->createAdapter();

        $identifiers1 = $adapter->findIdentifiers('building');
        $identifiers2 = $adapter->findIdentifiers('building');

        $this->assertSame($identifiers1, $identifiers2);
    }
}
