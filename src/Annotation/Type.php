<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Annotation;

/**
 * @author David Badura <d.a.badura@gmail.com>
 *
 * @Annotation
 */
class Type
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var bool
     */
    public $nullable;

    /**
     * @var array
     */
    public $options;

    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? null;
        $this->nullable = $data['nullable'] ?? false;
        $this->options = $data['options'] ?? [];

        if (isset($data['value'])) {
            $this->name = $data['value'];
        }
    }
}
