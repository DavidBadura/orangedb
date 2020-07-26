<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Metadata\Driver;

use DavidBadura\OrangeDb\Annotation\DiscriminatorColumn;
use DavidBadura\OrangeDb\Annotation\DiscriminatorMap;
use DavidBadura\OrangeDb\Annotation\Document;
use DavidBadura\OrangeDb\Annotation\EmbedMany;
use DavidBadura\OrangeDb\Annotation\EmbedOne;
use DavidBadura\OrangeDb\Annotation\Id;
use DavidBadura\OrangeDb\Annotation\Name;
use DavidBadura\OrangeDb\Annotation\ReferenceMany;
use DavidBadura\OrangeDb\Annotation\ReferenceOne;
use DavidBadura\OrangeDb\Annotation\Type;
use DavidBadura\OrangeDb\Metadata\ClassMetadata;
use DavidBadura\OrangeDb\Metadata\PropertyMetadata;
use DavidBadura\OrangeDb\Repository\DocumentRepository;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Metadata\Driver\AdvancedDriverInterface;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\Finder\Finder;
use Symfony\Component\String\Inflector\EnglishInflector;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class AnnotationDriver implements AdvancedDriverInterface
{
    private string $directory;
    private AnnotationReader $reader;
    private EnglishInflector $inflector;

    public function __construct(string $directory)
    {
        AnnotationRegistry::registerLoader('class_exists');

        $this->directory = $directory;
        $this->reader = new AnnotationReader();
        $this->inflector = new EnglishInflector();
    }

    public function loadMetadataForClass(ReflectionClass $class): ClassMetadata
    {
        $classMetadata = new ClassMetadata($class->getName());

        $classAnnotations = $this->reader->getClassAnnotations($class);

        foreach ($classAnnotations as $annotation) {
            if ($annotation instanceof Document) {
                $classMetadata->name = $annotation->name ?: strtolower($class->getShortName());
                $classMetadata->collection = $annotation->collection ?: $this->inflector->pluralize($classMetadata->name)[0];
                $classMetadata->repository = $annotation->repository ?: DocumentRepository::class;

                continue;
            }

            if ($annotation instanceof DiscriminatorColumn) {
                $classMetadata->discriminatorColumn = $annotation->name;

                continue;
            }

            if ($annotation instanceof DiscriminatorMap) {
                $classMetadata->discriminatorMap = $annotation->map;
                $classMetadata->discriminatorMapCallback = $annotation->callback;
            }
        }

        $classMetadata->identifier = $this->findIdentifer($class);

        foreach ($class->getProperties() as $property) {
            $propertyMetadata = $this->loadPropertyMetadata($property);

            if ($propertyMetadata) {
                $classMetadata->addPropertyMetadata($propertyMetadata);
            }
        }

        return $classMetadata;
    }

    private function findIdentifer(ReflectionClass $class): ?string
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

    private function loadPropertyMetadata(ReflectionProperty $property): ?PropertyMetadata
    {
        $propertyMetadata = new PropertyMetadata($property->getDeclaringClass()->getName(), $property->getName());
        $mapped = false;

        foreach ($this->reader->getPropertyAnnotations($property) as $propertyAnnotation) {
            if ($propertyAnnotation instanceof Type) {
                $propertyMetadata->type = $propertyAnnotation->name;
                $propertyMetadata->nullable = $propertyAnnotation->nullable;
                $propertyMetadata->options = $propertyAnnotation->options;

                $mapped = true;

                continue;
            }

            if ($propertyAnnotation instanceof ReferenceOne) {
                $propertyMetadata->reference = PropertyMetadata::REFERENCE_ONE;
                $propertyMetadata->target = $propertyAnnotation->target;

                $mapped = true;

                continue;
            }

            if ($propertyAnnotation instanceof ReferenceMany) {
                $propertyMetadata->reference = PropertyMetadata::REFERENCE_MANY;
                $propertyMetadata->target = $propertyAnnotation->target;

                $mapped = true;

                continue;
            }

            if ($propertyAnnotation instanceof EmbedOne) {
                $propertyMetadata->embed = PropertyMetadata::EMBED_ONE;
                $propertyMetadata->target = $propertyAnnotation->target;
                $propertyMetadata->mapping = $propertyAnnotation->mapping;

                $mapped = true;

                continue;
            }

            if ($propertyAnnotation instanceof EmbedMany) {
                $propertyMetadata->embed = PropertyMetadata::EMBED_MANY;
                $propertyMetadata->target = $propertyAnnotation->target;
                $propertyMetadata->mapping = $propertyAnnotation->mapping;

                $mapped = true;

                continue;
            }

            if ($propertyAnnotation instanceof Name) {
                $propertyMetadata->fieldName = $propertyAnnotation->name;

                $mapped = true;

                continue;
            }
        }

        return $mapped ? $propertyMetadata : null;
    }

    public function getAllClassNames(): array
    {
        $finder = new Finder();
        $files = $finder->in($this->directory)->files()->name('*.php')->getIterator();

        foreach ($files as $file) {
            require_once $file->getPathname();
        }

        $declared = get_declared_classes();
        $classes = [];

        foreach ($declared as $className) {
            $reflection = new ReflectionClass($className);
            $sourceFile = $reflection->getFileName();

            if (!$sourceFile || !str_starts_with($sourceFile, $this->directory)) {
                continue;
            }

            $classAnnotations = $this->reader->getClassAnnotations($reflection);

            foreach ($classAnnotations as $annotation) {
                if ($annotation instanceof Document) {
                    $classes[] = $className;

                    continue 2;
                }
            }
        }

        return $classes;
    }
}
