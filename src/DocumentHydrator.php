<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb;

use DavidBadura\OrangeDb\Loader\WrongTypeException;
use DavidBadura\OrangeDb\Metadata\ClassMetadata;
use DavidBadura\OrangeDb\Metadata\PropertyMetadata;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class DocumentHydrator
{
    private DocumentManager $manager;

    public function __construct(DocumentManager $manager)
    {
        $this->manager = $manager;
    }

    public function hydrate(string $class, array $data): object
    {
        $metadata = $this->manager->getMetadataFor($class);

        if ($metadata->discriminatorColumn) {
            $class = $this->findRealClass($metadata, $data);
            $metadata = $this->manager->getMetadataFor($class);
            unset($data[$metadata->discriminatorColumn]);
        }

        $object = $metadata->reflection->newInstanceWithoutConstructor();

        /** @var PropertyMetadata $property */
        foreach ($metadata->propertyMetadata as $property) {
            $value = null;

            if (isset($data[$property->name])) {
                $value = $data[$property->name];
                unset($data[$property->name]);
            }

            $this->populateValue($object, $value, $property);
        }

        if (count($data) > 0) {
            throw new DocumentMetadataException(
                sprintf('class %s does not map following fields: %s', $class, implode(', ', array_keys($data)))
            );
        }

        return $object;
    }

    /**
     * @param mixed $value
     */
    private function populateValue(object $object, $value, PropertyMetadata $property): void
    {
        if ($property->type) {
            if ($value === null && !$property->nullable) {
                throw new DocumentMetadataException(
                    sprintf('field "%s" in class "%s" is not nullable', $property->name, get_class($object))
                );
            }

            if ($value !== null) {
                $type = $this->manager->getTypeRegisty()->get($property->type);
                $value = $type->transformToPhp($value, $property->options);
            }

            $property->setValue($object, $value);

            return;
        }

        if (!$value) {
            return;
        }

        if ($property->reference === PropertyMetadata::REFERENCE_ONE && $property->target) {
            $property->setValue($object, $this->manager->find($property->target, $value));

            return;
        }

        if ($property->reference === PropertyMetadata::REFERENCE_MANY && $property->target) {
            $result = [];

            foreach ($value as $k => $v) {
                $result[] = $this->manager->find($property->target, $v);
            }

            $property->setValue($object, $result);

            return;
        }

        if ($property->embed === PropertyMetadata::EMBED_ONE && $property->target) {
            if ($property->mapping) {
                $value = $this->mapping($value, $property->mapping);
            }

            $property->setValue($object, $this->hydrate($property->target, $value));

            return;
        }

        if ($property->embed === PropertyMetadata::EMBED_MANY && $property->target) {
            $result = [];

            foreach ($value as $k => $v) {
                if ($property->mapping) {
                    $v = $this->mapping($v, $property->mapping);
                }

                $result[] = $this->hydrate($property->target, $v);
            }

            $property->setValue($object, $result);
        }
    }

    private function findRealClass(ClassMetadata $metadata, array $data): string
    {
        $class = $metadata->name;
        $type = $data[$metadata->discriminatorColumn];

        if ($metadata->discriminatorMapCallback) {
            $callback = $metadata->discriminatorMapCallback;
            $discriminatorClass = $class::$callback($type);
        } else {
            if (!is_array($metadata->discriminatorMap) || !array_key_exists($type, $metadata->discriminatorMap)) {
                throw new \RuntimeException();
            }

            $discriminatorClass = $metadata->discriminatorMap[$type];
        }

        if (!is_string($discriminatorClass)) {
            throw new \RuntimeException();
        }

        if ($discriminatorClass !== $class && !in_array($class, class_parents($discriminatorClass))) {
            throw new WrongTypeException($class, $discriminatorClass);
        }

        return $discriminatorClass;
    }

    /**
     * @param mixed $data
     * @param mixed $map
     */
    private function mapping($data, $map): array
    {
        if (!is_array($map)) {
            return [$map => $data];
        }

        $result = [];

        foreach ($map as $i => $key) {
            $result[$key] = $data[$i];
        }

        return $result;
    }
}
