<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @author David Badura <d.a.badura@gmail.com>
 *
 * @Annotation
 */
class DiscriminatorMap
{
    /**
     * @var array
     */
    public $map;

    public function __construct(array $data)
    {
        if (isset($data['value'])) {
            $this->map = $data['value'];
        }
    }
}
