<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Dumper;

use DavidBadura\OrangeDb\DocumentManager;
use DavidBadura\OrangeDb\Metadata\PropertyMetadata;

class Dumper
{
    private $manager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->manager = $documentManager;
    }

    public function dump(string $path, object $object): void
    {
        $content = "<?php\n\n";
        $content .= 'return '.$this->create($object).";\n";

        $this->write($path, $content);
    }

    public function dumpIndex(string $path, string $class, array $identifiers): void
    {
        $content = "<?php\n\n";
        $content .= "return [\n";

        foreach ($identifiers as $id) {
            $content .= "    '$id' => \$manager->find('$class', '$id'),\n";
        }

        $content .= '];';

        $this->write($path, $content);
    }

    private function create(object $object): string
    {
        $class = get_class($object);
        $body = $this->createBody($class, $object);

        $content = <<<CONTENT
\Closure::bind(function () use (\$manager) {
    \$self = (new ReflectionClass('$class'))->newInstanceWithoutConstructor();

$body
    return \$self;
}, null, '$class')()
CONTENT;

        return $content;
    }

    private function createBody(string $class, object $object): string
    {
        $properties = $this->prepareProperties($class, $object);

        $content = '';

        foreach ($properties as $property => $value) {
            $content .= "    \$self->$property = $value;\n";
        }

        return $content;
    }

    private function prepareProperties(string $class, object $object): array
    {
        $metadata = $this->manager->getMetadataFor($class);
        $properties = [];

        /** @var PropertyMetadata $property */
        foreach ($metadata->propertyMetadata as $property) {
            $value = $property->getValue($object);

            if ($string = $this->prepareProperty($value, $property)) {
                $properties[$property->name] = $string;
            }
        }

        return $properties;
    }

    private function prepareProperty($value, PropertyMetadata $property): ?string
    {
        if ($value === null) {
            return 'null';
        }

        if ($property->type) {
            $type = $this->manager->getTypeRegisty()->get($property->type);

            return $type->transformToDump($value, $property->options);
        }

        if ($property->reference === PropertyMetadata::REFERENCE_ONE) {
            $target = $property->target;
            $identifier = $this->manager->getMetadataFor($target)->getIdentifier($value);

            return "\$manager->find('$target', '$identifier')";
        }

        if ($property->reference === PropertyMetadata::REFERENCE_MANY) {
            if (count($value) === 0) {
                return '[]';
            }

            $content = "[\n";

            $target = $property->target;
            $targetMetadata = $this->manager->getMetadataFor($target);

            foreach ($value as $k => $v) {
                $identifier = $targetMetadata->getIdentifier($v);
                $content .= "        \$manager->find('$target', '$identifier'),\n";
            }

            return $content.'    ]';
        }

        if ($property->embed === PropertyMetadata::EMBED_ONE) {
            return $this->create($value);
        }

        if ($property->embed === PropertyMetadata::EMBED_MANY) {
            $content = "[\n";

            foreach ($value as $v) {
                $content .= '        '.$this->create($v).",\n";
            }

            return $content.'    ]';
        }

        return null;
    }

    private function write(string $path, $content)
    {
        $dir = dirname($path);

        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
            }
        }

        file_put_contents($path, $content);
    }
}
