<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test;

use DavidBadura\OrangeDb\Adapter\YamlAdapter;
use DavidBadura\OrangeDb\DocumentManager;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class DocumentManagerTest extends AbstractDocumentManagerTest
{
    protected function createDocumentManager(): DocumentManager
    {
        return new DocumentManager(
            __DIR__ . '/_files/demo.xml',
            __DIR__ . '/Fixture'
        );
    }
}
