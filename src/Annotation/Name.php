<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Annotation;

/**
 * @author David Badura <d.a.badura@gmail.com>
 *
 * @Annotation
 */
class Name
{
    public ?string $name;

    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? null;

        if (isset($data['value'])) {
            $this->name = $data['value'];
        }
    }
}
