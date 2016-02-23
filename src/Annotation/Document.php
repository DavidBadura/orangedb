<?php

namespace DavidBadura\OrangeDb\Annotation;

/**
 * @author David Badura <d.a.badura@gmail.com>
 *
 * @Annotation
 */
class Document
{
    /**
     * @var string
     */
    public $collection;

    /**
     * @var string
     */
    public $repository;
}