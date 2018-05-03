<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Annotation;

/**
 * @author David Badura <d.a.badura@gmail.com>
 *
 * @Annotation
 */
class EmbedOne
{
    /**
     * @var string
     */
    public $target;

    /**
     * @var array
     */
    public $mapping;

    public function __construct(array $data)
    {
        $this->target = $data['target'] ?? null;
        $this->mapping = $data['mapping'] ?? null;

        if (isset($data['value'])) {
            $this->target = $data['value'];
        }
    }
}
