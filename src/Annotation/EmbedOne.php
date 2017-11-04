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
}
