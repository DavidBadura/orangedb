<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb;

use DavidBadura\OrangeDb\Metadata\PropertyMetadata;
use Doctrine\Instantiator\Instantiator;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class DocumentHydrator
{
    private $manager;
    private $instantiator;

    public function __construct(DocumentManager $manager)
    {
        $this->manager = $manager;
        $this->instantiator = new Instantiator();
    }

    public function hydrate(string $class, array $data): object
    {
        $metadata = $this->manager->getMetadataFor($class);
        $object = $metadata->reflection->newInstanceWithoutConstructor();

        /** @var PropertyMetadata $property */
        foreach ($metadata->propertyMetadata as $property) {
            $value = null;

            if (isset($data[$property->name])) {
                $value = $data[$property->name];
                unset($data[$property->name]);
            }

            if ($property->type) {
                $type = $this->manager->getTypeRegisty()->get($property->type);
                $property->setValue($object, $type->transformToPhp($value, $property->options));
            }

            if (!$value) {
                continue;
            }

            if ($property->reference === PropertyMetadata::REFERENCE_ONE) {
                $property->setValue($object, $this->manager->find($property->target, $value));
            }

            if ($property->reference === PropertyMetadata::REFERENCE_MANY) {
                $result = [];

                foreach ($value as $k => $v) {
                    $result[] = $this->manager->find($property->target, $v);
                }

                $property->setValue($object, $result);
            }

            if ($property->embed === PropertyMetadata::EMBED_ONE) {
                if ($property->mapping) {
                    $value = $this->mapping($value, $property->mapping);
                }

                $property->setValue($object, $this->hydrate($property->target, $value));
            }

            if ($property->embed === PropertyMetadata::EMBED_MANY) {
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

        if (count($data) > 0) {
            throw new DocumentMetadataException(
                sprintf('class %s does not map following fields: %s', $class, implode(', ', array_keys($data)))
            );
        }

        return $object;
    }

    private function mapping(array $data, array $map): array
    {
        $result = [];

        foreach ($map as $i => $key) {
            $result[$key] = $data[$i];
        }

        return $result;
    }
}
