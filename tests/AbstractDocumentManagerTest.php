<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test;

use DavidBadura\OrangeDb\DocumentManager;
use DavidBadura\OrangeDb\DocumentMetadataException;
use DavidBadura\OrangeDb\Repository\DocumentRepository;
use DavidBadura\OrangeDb\Test\Fixture\Building;
use DavidBadura\OrangeDb\Test\Fixture\Material;
use DavidBadura\OrangeDb\Test\Fixture\MissingMapping;
use DavidBadura\OrangeDb\Test\Fixture\MissingProperties;
use DavidBadura\OrangeDb\Test\Fixture\Unknown;
use DavidBadura\OrangeDb\Test\Fixture\User;
use PHPUnit\Framework\TestCase;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
abstract class AbstractDocumentManagerTest extends TestCase
{
    abstract protected function createDocumentManager(): DocumentManager;

    public function testUnknownDocument()
    {
        $this->expectException(DocumentMetadataException::class);

        $this->createDocumentManager()->find(Unknown::class, 'foo');
    }

    public function testMissingProperties()
    {
        $this->expectException(DocumentMetadataException::class);

        $this->createDocumentManager()->find(MissingProperties::class, 'house');
    }

    public function testMissingMapping()
    {
        $this->expectException(DocumentMetadataException::class);

        $this->createDocumentManager()->find(MissingMapping::class, 'house');
    }

    public function testFind()
    {
        /** @var Building $object */
        $object = $this->createDocumentManager()->find(Building::class, 'house');

        $this->assertEquals('House', $object->getName());
        $this->assertEquals(5, $object->getConstructionRounds());
    }

    public function testFindSameObject()
    {
        $manager = $this->createDocumentManager();

        /** @var Building $object */
        $object1 = $manager->find(Building::class, 'house');
        $object2 = $manager->find(Building::class, 'house');

        self::assertSame($object1, $object2);
    }

    public function testFindAfterClear()
    {
        $manager = $this->createDocumentManager();

        /** @var Building $object */
        $object1 = $manager->find(Building::class, 'house');

        $manager->clear();

        $object2 = $manager->find(Building::class, 'house');

        self::assertEquals($object1, $object2);
        self::assertNotSame($object1, $object2);
    }

    public function testRepository()
    {
        $repository = $this->createDocumentManager()->getRepository(Building::class);

        self::assertInstanceOf(DocumentRepository::class, $repository);
    }

    public function testFindAll()
    {
        $repository = $this->createDocumentManager()->getRepository(Material::class);

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

    public function testFindAllWithCache()
    {
        $repository = $this->createDocumentManager()->getRepository(Material::class);

        $materials1 = $repository->findAll();
        $materials2 = $repository->findAll();

        self::assertEquals($materials1, $materials2);

        self::assertSame($materials1[0], $materials2[0]);
        self::assertSame($materials1[1], $materials2[1]);
    }

    public function testFindAllAfterCacheClear()
    {
        $manager = $this->createDocumentManager();
        $repository = $manager->getRepository(Material::class);

        $materials1 = $repository->findAll();

        $manager->clear();

        $materials2 = $repository->findAll();

        self::assertEquals($materials1, $materials2);

        self::assertEquals($materials1[0], $materials2[0]);
        self::assertNotSame($materials1[0], $materials2[0]);

        self::assertEquals($materials1[1], $materials2[1]);
        self::assertNotSame($materials1[1], $materials2[1]);
    }

    public function testComplexLoad()
    {
        $manager = $this->createDocumentManager();
        $repository = $manager->getRepository(User::class);

        /** @var User $john */
        $john = $repository->find('john');

        self::assertEquals('john', $john->getId());
        self::assertEquals('John', $john->getName());
        self::assertEquals(new \DateTime('1989-01-08'), $john->getBirthdate());
    }
}
