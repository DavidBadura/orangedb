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
    public $nullable = false;

    /**
     * @var array
     */
    public $options = [];
}
