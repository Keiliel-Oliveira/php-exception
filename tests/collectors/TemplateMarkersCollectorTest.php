<?php

declare(strict_types = 1);
use KeilielOliveira\PhpException\Collectors\TemplateMarkersCollector;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class TemplateMarkersCollectorTest extends TestCase {
    public function testCollectIsCollecting(): void {
        $template = '{[A]key}, {key}';

        $expected = ['[A]key', 'key'];
        $returned = new TemplateMarkersCollector( $template )->collect();

        $this->assertEquals( $expected, $returned );
    }
}
