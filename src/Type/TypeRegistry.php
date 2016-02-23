<?php

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

    /**
     *
     */
    public function __construct()
    {
        $this->addType(new StringType());
        $this->addType(new IntegerType());
        $this->addType(new BooleanType());
        $this->addType(new ArrayType());
        $this->addType(new DateTimeType());
    }

    /**
     * @param TypeInterface $type
     * @throws \Exception
     */
    public function addType(TypeInterface $type)
    {
        if (isset($this->types[$type->getName()])) {
            throw new \Exception(); // todo
        }

        $this->types[$type->getName()] = $type;
    }

    /**
     * @param string $name
     * @return TypeInterface
     * @throws \Exception
     */
    public function get($name)
    {
        if (isset($this->types[$name])) {
            return $this->types[$name];
        }

        throw new \Exception(); // todo
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->types[$name]);
    }
}