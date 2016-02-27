<?php

namespace DavidBadura\OrangeDb\Test\Adapter;

use DavidBadura\OrangeDb\DocumentLoader;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class DocumentLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DocumentLoader
     */
    private $loader;

    public function setUp()
    {
        $this->adapter = new DocumentLoader();
    }
}