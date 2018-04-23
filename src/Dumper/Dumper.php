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
        $content .= 'return '.$this->create(get_class($object), $object).";\n";

        $this->write($path, $content);
    }

    public function dumpIndex(string $path, string $class, array $identifiers): void
    {
        $content = "<?php\n\n";
        $content .= "return [\n";

        foreach ($identifiers as $id) {
            $content .= "    \$manager->find('$class', '$id'),\n";
        }

        $content .= '];';

        $this->write($path, $content);
    }

    private function create(string $class, object $object): string
    {
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

            if ($value === null) {
                $properties[$property->name] = "null";

                continue;
            }

            if ($property->type) {
                $type = $this->manager->getTypeRegisty()->get($property->type);

                $properties[$property->name] = $type->transformToDump($value, $property->options);

                continue;
            }

            if ($property->reference === PropertyMetadata::REFERENCE_ONE) {
                $target = $property->target;
                $identifier = $this->manager->getMetadataFor($target)->getIdentifier($value);

                $properties[$property->name] = "\$manager->find('$target', '$identifier')";

                continue;
            }

            if ($property->reference === PropertyMetadata::REFERENCE_MANY) {
                if (count($value) === 0) {
                    $properties[$property->name] = '[]';

                    continue;
                }

                $content = "[\n";

                $target = $property->target;
                $targetMetadata = $this->manager->getMetadataFor($target);

                foreach ($value as $k => $v) {
                    $identifier = $targetMetadata->getIdentifier($v);
                    $content .= "        \$manager->find('$target', '$identifier'),\n";
                }

                $properties[$property->name] = $content.'    ]';

                continue;
            }

            if ($property->embed === PropertyMetadata::EMBED_ONE) {
                $properties[$property->name] = $this->create($property->target, $value);
            }

            if ($property->embed === PropertyMetadata::EMBED_MANY) {
                $content = "[\n";

                foreach ($value as $v) {
                    $content .= '        '.$this->create($property->target, $v).",\n";
                }

                $properties[$property->name] = $content.'    ]';
            }
        }

        return $properties;
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
