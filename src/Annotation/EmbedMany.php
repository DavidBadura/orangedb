<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Annotation;

/**
 * @author David Badura <d.a.badura@gmail.com>
 *
 * @Annotation
 */
class EmbedMany
{
    /**
     * @var string
     */
    public $target;

    /**
     * @var array
     */
    public $mapping;
}
