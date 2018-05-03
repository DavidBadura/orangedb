<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test;

use DavidBadura\OrangeDb\DocumentManager;
use DavidBadura\OrangeDb\DocumentMetadataException;
use DavidBadura\OrangeDb\Loader\WrongTypeException;
use DavidBadura\OrangeDb\Repository\DocumentRepository;
use DavidBadura\OrangeDb\Test\Fixture\Building;
use DavidBadura\OrangeDb\Test\Fixture\EmbeddedInheritance;
use DavidBadura\OrangeDb\Test\Fixture\InheritanceBar;
use DavidBadura\OrangeDb\Test\Fixture\InheritanceBase;
use DavidBadura\OrangeDb\Test\Fixture\InheritanceFoo;
use DavidBadura\OrangeDb\Test\Fixture\Material;
use DavidBadura\OrangeDb\Test\Fixture\MissingMapping;
use DavidBadura\OrangeDb\Test\Fixture\MissingProperties;
use DavidBadura\OrangeDb\Test\Fixture\Super;
use DavidBadura\OrangeDb\Test\Fixture\TraitFixture;
use DavidBadura\OrangeDb\Test\Fixture\Unknown;
use DavidBadura\OrangeDb\Test\Fixture\User;
use DavidBadura\OrangeDb\Test\Fixture\ValueObject;
use DavidBadura\OrangeDb\Test\Fixture\ValueObjectOwner;
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

    public function testExtends()
    {
        $manager = $this->createDocumentManager();
        $repository = $manager->getRepository(Super::class);

        /** @var Super $object */
        $object = $repository->find('wood');

        self::assertEquals('wood', $object->getId());
        self::assertEquals('Wood', $object->getName());
        self::assertEquals(12, $object->age);
        self::assertEquals(42, $object->getTest());
    }

    public function testTrait()
    {
        $manager = $this->createDocumentManager();
        $repository = $manager->getRepository(TraitFixture::class);

        /** @var TraitFixture $object */
        $object = $repository->find('test');

        self::assertEquals('Foo', $object->name);
        self::assertEquals(42, $object->age);
    }

    public function testInheritanceBase()
    {
        $manager = $this->createDocumentManager();
        $repository = $manager->getRepository(InheritanceBase::class);

        /** @var InheritanceBase|InheritanceFoo $test1 */
        $test1 = $repository->find('test');

        self::assertEquals('Foo', $test1->name);
        self::assertEquals(42, $test1->age);

        /** @var InheritanceBase|InheritanceBar $test2 */
        $test2 = $repository->find('test2');

        self::assertEquals('BAR', $test2->name);
        self::assertEquals('hello', $test2->baz);

        self::assertCount(2, $repository->findAll());

        $repository = $manager->getRepository(InheritanceFoo::class);

        /** @var InheritanceBase|InheritanceFoo $test1 */
        $test1 = $repository->find('test');

        self::assertEquals('Foo', $test1->name);
        self::assertEquals(42, $test1->age);

        self::assertCount(1, $repository->findAll());

        $repository = $manager->getRepository(InheritanceBar::class);

        /** @var InheritanceBase|InheritanceBar $test2 */
        $test2 = $repository->find('test2');

        self::assertEquals('BAR', $test2->name);
        self::assertEquals('hello', $test2->baz);

        self::assertCount(1, $repository->findAll());
    }

    public function testInheritanceNotFound()
    {
        self::expectException(WrongTypeException::class);

        $manager = $this->createDocumentManager();

        $repository = $manager->getRepository(InheritanceFoo::class);
        $repository->find('test2');
    }

    public function testEmbeddedInheritance()
    {
        $manager = $this->createDocumentManager();

        $repository = $manager->getRepository(EmbeddedInheritance::class);

        /** @var EmbeddedInheritance $object */
        $object = $repository->find('test');

        self::assertInstanceOf(InheritanceFoo::class, $object->test);
        self::assertEquals('BAR!', $object->test->name);
        self::assertEquals(42, $object->test->age);
    }

    public function testValueObjectMapping()
    {
        $manager = $this->createDocumentManager();

        $repository = $manager->getRepository(ValueObjectOwner::class);

        /** @var ValueObjectOwner $object */
        $object = $repository->find('test');

        self::assertInstanceOf(ValueObject::class, $object->type);
        self::assertEquals('foo', $object->type->name);
    }
}
