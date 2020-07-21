<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Annotation;

/**
 * @author David Badura <d.a.badura@gmail.com>
 *
 * @Annotation
 */
class Type
{
    public ?string $name;
    public bool $nullable;
    public array $options;

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
