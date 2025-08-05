<?php

declare(strict_types = 1);
use KeilielOliveira\PhpException\Collectors\ContextCollector;
use KeilielOliveira\PhpException\Facades\Context;
use KeilielOliveira\PhpException\Facades\Handler;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ContextCollectorTest extends TestCase {
    public function setUp(): void {
        if ( !Handler::has( 'A' ) ) {
            Context::set( 'true', true );
            Handler::save( 'A', Context::getContext() );

            Context::newContext();
            Context::set( 'false', false );
        }
    }

    #[DataProvider( 'provideMarkersAndExpectedReturnToCollector' )]
    public function testCollectIsReturningExpectedContextValue( string $marker, string $expected ): void {
        $returned = new ContextCollector( $marker )->collect();

        $this->assertEquals( $expected, $returned );
    }

    /**
     * @return string[][]
     */
    public static function provideMarkersAndExpectedReturnToCollector(): array {
        return [
            [
                'false',
                'false',
            ],
            [
                '[A]true',
                'true',
            ],
            [
                'true[A]',
                'true',
            ],
        ];
    }
}
