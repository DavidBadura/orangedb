<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test\Fixture;

use DavidBadura\OrangeDb\Annotation\Document;

/**
 * @Document(collection="building")
 */
class MissingMapping
{
    private $name;
    private $constructionRounds;
    private $constructionCosts;

    public function getName()
    {
        return $this->name;
    }

    public function getConstructionRounds()
    {
        return $this->constructionRounds;
    }

    public function getConstructionCosts()
    {
        return $this->constructionCosts;
    }
}