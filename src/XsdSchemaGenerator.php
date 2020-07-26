<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb;

use DavidBadura\OrangeDb\Metadata\ClassMetadata;
use DavidBadura\OrangeDb\Metadata\PropertyMetadata;
use DavidBadura\XsdBuilder\Attribute;
use DavidBadura\XsdBuilder\Builder;
use DavidBadura\XsdBuilder\ComplexType;
use DavidBadura\XsdBuilder\Element;
use DavidBadura\XsdBuilder\Key;

class XsdSchemaGenerator
{
    private DocumentManager $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function generate(): string
    {
        $builder = new Builder();
        $rootType = new ComplexType();


        $classes = $this->documentManager->getAllClasses();

        foreach ($classes as $class) {
            $rootType->addElement($this->generateFor($class));
        }

        $builder->addElement(Element::complexType('root', $rootType));

        return $builder->toString();
    }

    private function generateFor(string $class): Element
    {
        $metadata = $this->documentManager->getMetadataFor($class);

        if (!$metadata->collection) {
            throw new \RuntimeException();
        }

        return $this->createElementWithMetadata($metadata->collection, $metadata->name, $metadata);
    }

    private function createElementWithMetadata(string $collection, string $name, ClassMetadata $metadata, bool $many = false): Element
    {
        $entityType = new ComplexType();

        /** @var PropertyMetadata $propertyMetadata */
        foreach ($metadata->propertyMetadata as $propertyMetadata) {
            $name = $propertyMetadata->name;

            if (!is_string($name)) {
                throw new \RuntimeException();
            }

            if ($name === $metadata->identifier) {
                continue;
            }

            if ($propertyMetadata->type) {
                $entityType->addElement($this->createSimpleElement($propertyMetadata));

                continue;
            }

            if ($propertyMetadata->reference === PropertyMetadata::REFERENCE_ONE) {
                $element = Element::string($name);
                $entityType->addElement($element);

                continue;
            }

            if ($propertyMetadata->reference === PropertyMetadata::REFERENCE_MANY) {
                $element = Element::string($name);
                $element->setUnbounded();
                $entityType->addElement($element);

                continue;
            }

            /*
            if ($propertyMetadata->embed === PropertyMetadata::EMBED_ONE) {
                $targetMetadata = $this->documentManager->getMetadataFor($propertyMetadata->target);

                $entity->addElement($this->createElementWithMetadata($name, $name, $targetMetadata));
                continue;
            }

            if ($propertyMetadata->embed === PropertyMetadata::EMBED_MANY) {
                $targetMetadata = $this->documentManager->getMetadataFor($propertyMetadata->target);

                $entity->addElement($this->createElementWithMetadata($name, $name, $targetMetadata, true));
                continue;
            }
            */
        }

        $idAttribute = Attribute::string('id');
        $idAttribute->setUse('required');
        $entityType->addAttribute($idAttribute);

        $collectionType = new ComplexType();
        $collectionElement = Element::complexType($metadata->collection, $collectionType);
        $collectionElement->setMinOccurs(0);

        $entityElement = Element::complexType($metadata->name, $entityType);
        $entityElement->setMinOccurs(0);
        $entityElement->setUnbounded();

        $keyName = sprintf('%s-id', $metadata->name);
        $entityElement->setKey(Key::create($keyName, $metadata->name, ['@id']));
        $collectionType->addElement($entityElement);

        return $collectionElement;
    }

    private function createSimpleElement(PropertyMetadata $metadata): Element
    {
        $name = $metadata->name;
        $type = $this->documentManager->getTypeRegisty()->get($metadata->type);

        return Element::create($name, $type->getXsdType());
    }
}
