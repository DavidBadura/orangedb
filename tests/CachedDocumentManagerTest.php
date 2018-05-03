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
    public function setUp()
    {
        (new Filesystem())->remove(__DIR__.'/_cache');
    }

    public function tearDown()
    {
        (new Filesystem())->remove(__DIR__.'/_cache');
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

    protected function createDocumentManager(): DocumentManager
    {
        return new DocumentManager(
            new YamlAdapter(__DIR__.'/_files/yaml'),
            __DIR__.'/_cache'
        );
    }
}
