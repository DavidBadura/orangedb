<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Annotation;

/**
 * @author David Badura <d.a.badura@gmail.com>
 *
 * @Annotation
 */
class Document
{
    public ?string $collection;
    public ?string $repository;

    public function __construct(array $data)
    {
        $this->collection = $data['collection'] ?? null;
        $this->repository = $data['repository'] ?? null;

        if (isset($data['value'])) {
            $this->collection = $data['value'];
        }
    }
}
