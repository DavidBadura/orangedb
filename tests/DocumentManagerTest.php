<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test;

use DavidBadura\OrangeDb\Adapter\YamlAdapter;
use DavidBadura\OrangeDb\DocumentManager;
use DavidBadura\OrangeDb\DocumentMetadataException;
use DavidBadura\OrangeDb\Test\Fixture\Building;
use DavidBadura\OrangeDb\Test\Fixture\MissingMapping;
use DavidBadura\OrangeDb\Test\Fixture\MissingProperties;
use DavidBadura\OrangeDb\Test\Fixture\Unknown;
use PHPUnit\Framework\TestCase;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class DocumentManagerTest extends TestCase
{
    /**
     * @var DocumentManager
     */
    private $manager;

    public function setUp()
    {
        $this->manager = new DocumentManager(
            new YamlAdapter(__DIR__.'/_files/yaml')
        );
    }

    public function testUnknownDocument()
    {
        $this->expectException(DocumentMetadataException::class);

        $this->manager->find(Unknown::class, 'foo');
    }

    public function testMissingProperties()
    {
        $this->expectException(DocumentMetadataException::class);

        $this->manager->find(MissingProperties::class, 'house');
    }

    public function testMissingMapping()
    {
        $this->expectException(DocumentMetadataException::class);

        $this->manager->find(MissingMapping::class, 'house');
    }

    public function testFind()
    {
        /** @var Building $object */
        $object = $this->manager->find(Building::class, 'house');

        $this->assertEquals('House', $object->getName());
        $this->assertEquals(5, $object->getConstructionRounds());
    }
}
