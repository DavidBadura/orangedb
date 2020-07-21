<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Annotation;

/**
 * @author David Badura <d.a.badura@gmail.com>
 *
 * @Annotation
 */
class ReferenceOne
{
    public ?string $target;

    public function __construct(array $data)
    {
        $this->target = $data['target'] ?? null;

        if (isset($data['value'])) {
            $this->target = $data['value'];
        }
    }
}
