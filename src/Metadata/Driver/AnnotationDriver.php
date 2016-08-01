<?php

namespace DavidBadura\OrangeDb\Metadata\Driver;

use DavidBadura\OrangeDb\Annotation\Document;
use DavidBadura\OrangeDb\Annotation\EmbedMany;
use DavidBadura\OrangeDb\Annotation\EmbedOne;
use DavidBadura\OrangeDb\Annotation\Id;
use DavidBadura\OrangeDb\Annotation\ReferenceKey;
use DavidBadura\OrangeDb\Annotation\ReferenceMany;
use DavidBadura\OrangeDb\Annotation\ReferenceOne;
use DavidBadura\OrangeDb\Annotation\Type;
use DavidBadura\OrangeDb\Metadata\ClassMetadata;
use DavidBadura\OrangeDb\Metadata\PropertyMetadata;
use DavidBadura\OrangeDb\Repository\DocumentRepository;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Metadata\Driver\DriverInterface;
use ReflectionClass;
use ReflectionProperty;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class AnnotationDriver implements DriverInterface
{
    /**
     * @var AnnotationReader
     */
    private $reader;

    /**
     *
     */
    public function __construct()
    {
        AnnotationRegistry::registerLoader('class_exists');

        $this->reader = new AnnotationReader();
    }

    /**
     * @param ReflectionClass $class
     *
     * @return ClassMetadata
     */
    public function loadMetadataForClass(ReflectionClass $class)
    {
        $classMetadata = new ClassMetadata($class->getName());

        $classAnnotations = $this->reader->getClassAnnotations($class);

        foreach ($classAnnotations as $annotation) {
            if ($annotation instanceof Document) {
                $classMetadata->collection = $annotation->collection ?: strtolower($class->getShortName());
                $classMetadata->repository = $annotation->repository ?: DocumentRepository::class;
            }
        }

        $classMetadata->identifier = $this->findIdentifer($class);

        foreach ($class->getProperties() as $property) {
            $classMetadata->addPropertyMetadata($this->loadPropertyMetadata($property));
        }

        return $classMetadata;
    }

    /**
     * @param ReflectionClass $class
     * @return string
     * @throws \Exception
     */
    private function findIdentifer(ReflectionClass $class)
    {
        foreach ($class->getProperties() as $property) {
            foreach ($this->reader->getPropertyAnnotations($property) as $propertyAnnotation) {
                if ($propertyAnnotation instanceof Id) {
                    return $property->getName();
                }
            }
        }

        return null;
    }

    /**
     * @param ReflectionProperty $property
     * @return PropertyMetadata
     */
    private function loadPropertyMetadata(ReflectionProperty $property)
    {
        $propertyMetadata = new PropertyMetadata($property->getDeclaringClass()->getName(), $property->getName());

        foreach ($this->reader->getPropertyAnnotations($property) as $propertyAnnotation) {
            if ($propertyAnnotation instanceof Type) {
                $propertyMetadata->type = $propertyAnnotation->name;
            }

            if ($propertyAnnotation instanceof ReferenceOne) {
                $propertyMetadata->reference = PropertyMetadata::REFERENCE_ONE;
                $propertyMetadata->target = $propertyAnnotation->target;
            }

            if ($propertyAnnotation instanceof ReferenceMany) {
                $propertyMetadata->reference = PropertyMetadata::REFERENCE_MANY;
                $propertyMetadata->target = $propertyAnnotation->target;
            }

            if ($propertyAnnotation instanceof ReferenceKey) {
                $propertyMetadata->reference = PropertyMetadata::REFERENCE_KEY;
                $propertyMetadata->target = $propertyAnnotation->target;

                if ($propertyAnnotation->value instanceof Type) {
                    $propertyMetadata->value = [
                        'type' => 'type',
                        'name' => $propertyAnnotation->value->name
                    ];
                }
            }

            if ($propertyAnnotation instanceof EmbedOne) {
                $propertyMetadata->embed = PropertyMetadata::EMBED_ONE;
                $propertyMetadata->target = $propertyAnnotation->target;
                $propertyMetadata->mapping = $propertyAnnotation->mapping;
            }

            if ($propertyAnnotation instanceof EmbedMany) {
                $propertyMetadata->embed = PropertyMetadata::EMBED_MANY;
                $propertyMetadata->target = $propertyAnnotation->target;
                $propertyMetadata->mapping = $propertyAnnotation->mapping;
            }
        }

        return $propertyMetadata;
    }
}