<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Annotation;

/**
 * @author David Badura <d.a.badura@gmail.com>
 *
 * @Annotation
 */
class ReferenceKey
{
    /**
     * @var string
     */
    public $target;

    /**
     * @var \DavidBadura\OrangeDb\Annotation\Type
     */
    public $value;
}
