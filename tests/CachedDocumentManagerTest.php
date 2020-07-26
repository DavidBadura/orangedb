<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test;

use DavidBadura\OrangeDb\Adapter\YamlAdapter;
use DavidBadura\OrangeDb\DocumentManager;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class CachedDocumentManagerTest extends AbstractDocumentManagerTest
{
    public function setUp(): void
    {
        (new Filesystem())->remove(__DIR__ . '/_cache');
    }

    public function tearDown(): void
    {
        (new Filesystem())->remove(__DIR__ . '/_cache');
    }

    public function testExtends()
    {
        parent::testExtends();
        parent::testExtends();
    }

    public function testTrait()
    {
        parent::testTrait();
        parent::testTrait();
    }

    public function testInheritanceBase()
    {
        parent::testInheritanceBase();
        parent::testInheritanceBase();
    }

    public function testEmbeddedInheritance()
    {
        parent::testEmbeddedInheritance();
        parent::testEmbeddedInheritance();
    }

    public function testValueObjectMapping()
    {
        parent::testValueObjectMapping();
        parent::testValueObjectMapping();
    }

    protected function createDocumentManager(): DocumentManager
    {
        return new DocumentManager(
            __DIR__ . '/_files/demo.xml',
            __DIR__ . '/Fixture',
            __DIR__ . '/_cache'
        );
    }
}
