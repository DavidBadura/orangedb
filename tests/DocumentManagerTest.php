<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test;

use DavidBadura\OrangeDb\Adapter\YamlAdapter;
use DavidBadura\OrangeDb\DocumentManager;
use DavidBadura\OrangeDb\DocumentMetadataException;
use DavidBadura\OrangeDb\Repository\DocumentRepository;
use DavidBadura\OrangeDb\Test\Fixture\Building;
use DavidBadura\OrangeDb\Test\Fixture\Material;
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

    public function testFindSameObject()
    {
        /** @var Building $object */
        $object1 = $this->manager->find(Building::class, 'house');
        $object2 = $this->manager->find(Building::class, 'house');

        self::assertSame($object1, $object2);
    }

    public function testFindAfterClear()
    {
        /** @var Building $object */
        $object1 = $this->manager->find(Building::class, 'house');

        $this->manager->clear();

        $object2 = $this->manager->find(Building::class, 'house');

        self::assertNotSame($object1, $object2);
    }

    public function testRepository()
    {
        $repository = $this->manager->getRepository(Building::class);

        self::assertInstanceOf(DocumentRepository::class, $repository);
    }

    public function testFindAll()
    {
        $repository = $this->manager->getRepository(Material::class);

        /** @var Material[]|array $materials */
        $materials = $repository->findAll();

        self::assertCount(2, $materials);

        self::assertEquals('stone', $materials[0]->getId());
        self::assertEquals('Stone', $materials[0]->getName());
        self::assertEquals(false, $materials[0]->isFood());

        self::assertEquals('wood', $materials[1]->getId());
        self::assertEquals('Wood', $materials[1]->getName());
        self::assertEquals(false, $materials[1]->isFood());
    }
}
