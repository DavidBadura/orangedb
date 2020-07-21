<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Type;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class TypeRegistry
{
    /**
     * @var TypeInterface[]
     */
    protected array $types = [];

    public function __construct(array $types = [])
    {
        foreach ($types as $type) {
            $this->addType($type);
        }
    }

    public function addType(TypeInterface $type)
    {
        if (isset($this->types[$type->getName()])) {
            throw new TypeException(sprintf('type with the name "%s" exists already', $type->getName()));
        }

        $this->types[$type->getName()] = $type;
    }

    public function get(string $name): TypeInterface
    {
        if (isset($this->types[$name])) {
            return $this->types[$name];
        }

        throw new TypeException(sprintf('type "%s" is not registered', $name));
    }

    public function has(string $name): bool
    {
        return isset($this->types[$name]);
    }

    public function all(): array
    {
        return array_values($this->types);
    }

    public static function createWithBuiltinTypes()
    {
        return new self([
            new StringType(),
            new IntegerType(),
            new FloatType(),
            new BooleanType(),
            new ArrayType(),
            new DateTimeType(),
        ]);
    }
}
