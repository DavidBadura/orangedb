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
    protected $types;

    public function __construct()
    {
        $this->addType(new StringType());
        $this->addType(new IntegerType());
        $this->addType(new BooleanType());
        $this->addType(new ArrayType());
        $this->addType(new DateTimeType());
    }

    public function addType(TypeInterface $type)
    {
        if (isset($this->types[$type->getName()])) {
            throw new \RuntimeException(); // todo
        }

        $this->types[$type->getName()] = $type;
    }

    public function get(string $name): TypeInterface
    {
        if (isset($this->types[$name])) {
            return $this->types[$name];
        }

        throw new \RuntimeException(); // todo
    }

    public function has(string $name): bool
    {
        return isset($this->types[$name]);
    }
}
