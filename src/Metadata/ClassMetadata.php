<?php

namespace DavidBadura\OrangeDb\Metadata;

use Metadata\MergeableClassMetadata;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class ClassMetadata extends MergeableClassMetadata
{
    /**
     * @var string
     */
    public $package;

    /**
     * @var string
     */
    public $identifier;

    /**
     * @var string
     */
    public $repository;
}