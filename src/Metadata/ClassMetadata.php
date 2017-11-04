<?php declare(strict_types=1);

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
    public $collection;

    /**
     * @var string|null
     */
    public $identifier;

    /**
     * @var string
     */
    public $repository;
}
