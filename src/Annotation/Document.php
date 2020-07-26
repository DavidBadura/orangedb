<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Annotation;

use Symfony\Component\String\Inflector\EnglishInflector;

/**
 * @author David Badura <d.a.badura@gmail.com>
 *
 * @Annotation
 */
class Document
{
    public ?string $name;
    public ?string $collection;
    public ?string $repository;

    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? null;
        $this->collection = $data['collection'] ?? null;
        $this->repository = $data['repository'] ?? null;

        if (isset($data['value'])) {
            $this->name = $data['value'];
        }
    }
}
