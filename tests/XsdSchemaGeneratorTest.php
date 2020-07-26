<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Test;

use DavidBadura\OrangeDb\DocumentManager;
use DavidBadura\OrangeDb\XsdSchemaGenerator;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class XsdSchemaGeneratorTest extends TestCase
{
    use MatchesSnapshots;

    private function createSchemaGenerator(): XsdSchemaGenerator
    {
        return new XsdSchemaGenerator(
            new DocumentManager(
                __DIR__ . '/_files/demo.xml',
                __DIR__ . '/Fixture'
            )
        );
    }

    public function testGenerateSchema(): void
    {
        $xsd = $this->createSchemaGenerator()->generate();
        $this->assertMatchesSnapshot($xsd);
    }
}
