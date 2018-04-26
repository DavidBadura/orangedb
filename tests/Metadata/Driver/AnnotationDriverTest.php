<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Metadata\Driver;

use DavidBadura\OrangeDb\Metadata\ClassMetadata;
use DavidBadura\OrangeDb\Metadata\Driver\AnnotationDriver;
use DavidBadura\OrangeDb\Metadata\PropertyMetadata;
use DavidBadura\OrangeDb\Repository\DocumentRepository;
use DavidBadura\OrangeDb\Test\Fixture\Building;
use PHPUnit\Framework\TestCase;

class AnnotationDriverTest extends TestCase
{
    public function testSimple()
    {
        $metadata = $this->load(Building::class);

        self::assertEquals('id', $metadata->identifier);
        self::assertEquals(DocumentRepository::class, $metadata->repository);
        self::assertEquals('building', $metadata->collection);

        /** @var PropertyMetadata[]|array $properties */
        $properties = $metadata->propertyMetadata;

        self::assertCount(3, $properties);

        self::assertArrayHasKey('id', $properties);
        self::assertEquals('id', $properties['id']->name);
        self::assertEquals('string', $properties['id']->type);

        self::assertArrayHasKey('name', $properties);
        self::assertEquals('name', $properties['name']->name);
        self::assertEquals('string', $properties['name']->type);

        self::assertArrayHasKey('constructionRounds', $properties);
        self::assertEquals('constructionRounds', $properties['constructionRounds']->name);
        self::assertEquals('int', $properties['constructionRounds']->type);
    }

    protected function load(string $class): ClassMetadata
    {
        return $this->createDriver()->loadMetadataForClass(new \ReflectionClass($class));
    }

    protected function createDriver()
    {
        return new AnnotationDriver();
    }
}