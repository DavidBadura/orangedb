<?php

namespace DavidBadura\OrangeDb\Metadata\Driver;

use DavidBadura\OrangeDb\Annotation\Id;
use DavidBadura\OrangeDb\Annotation\Type;
use DavidBadura\OrangeDb\Metadata\ClassMetadata;
use DavidBadura\OrangeDb\Metadata\PropertyMetadata;
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

        throw new \Exception(); // todo
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
        }

        return $propertyMetadata;
    }
}