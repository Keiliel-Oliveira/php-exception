<?php

declare(strict_types = 1);
use KeilielOliveira\PhpException\Context;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ContextTest extends TestCase {
    private Context $context;

    public function setUp(): void {
        $this->context = new Context();
    }

    public function testSetAndGetIsAddingAndReturningContext(): void {
        $this->context->set( 'key', true );
        $this->assertTrue( $this->context->get( 'key' ) );
    }

    public function testGetWithCallbackIsReturning(): void {
        $this->context->set( 'key', [1, 2, 3, 4] );
        $expected = [2, 4];
        $returned = $this->context->get( 'key', function ( mixed $context ): mixed {
            return array_values( array_filter( $context, function ( int $i ): bool {
                return 0 == $i % 2;
            } ) );
        } );

        $this->assertEquals( $expected, $returned );
    }

    public function testGetAllWithCallbackIsReturning(): void {
        $this->context->set( 'A', 'string' );
        $this->context->set( 'B', 10 );
        $this->context->set( 'C', true );

        $expected = ['B' => 10];
        $returned = $this->context->getAll( function ( mixed $context ): mixed {
            return array_filter( $context, function ( mixed $v ) {
                return is_int( $v );
            } );
        } );

        $this->assertEquals( $expected, $returned );
    }

    public function testHasIsValidating(): void {
        $this->context->set( 'key', 'value' );

        $this->assertTrue( $this->context->has( 'key' ) );
    }

    public function testForceSetIsAutomaticallyReplacingContext(): void {
        $this->context->set( 'key', 'value' );

        $expected = 'new value';
        $this->context->forceSet( 'key', $expected );
        $returned = $this->context->get( 'key' );

        $this->assertEquals( $expected, $returned );
    }

    public function testRemoveIsRemovingContext(): void {
        $this->context->set( 'A', 'string' );
        $this->context->set( 'B', 10 );
        $this->context->set( 'C', true );

        $this->context->delete( 'A', 'C' );
        $expected = ['B' => 10];
        $returned = $this->context->getAll();

        $this->assertEquals( $expected, $returned );
    }

    public function testClearWithCallbackIsCleaningContext(): void {
        $this->context->set( 'A', 'string' );
        $this->context->set( 'B', 10 );
        $this->context->set( 'C', true );

        $this->context->clear( function ( mixed $context ): mixed {
            return array_filter( $context, function ( mixed $v ) {
                return is_bool( $v );
            } );
        } );
        $expected = ['C' => true];
        $returned = $this->context->getAll();

        $this->assertEquals( $expected, $returned );
    }

    public function testIfHasIsAuthorizing(): void {
        $this->assertNull( $this->context->ifHas( 'B' )->get( 'B' ) );
    }

    public function testIfNotHasIsAuthorizing(): void {
        $this->context->set( 'A', true );

        $this->assertNull( $this->context->ifNotHas( 'A' )->get( 'A' ) );
    }

    public function testWhenIsAuthorizing(): void {
        $this->context->set( 'A', 10 );

        $this->assertNull(
            $this->context->when( function ( Context $c ): bool {
                return $c->get( 'A' ) < 5;
            } )->get( 'A' )
        );
    }

    public function testSeparateIsGettingContext(): void {
        try {
            $this->context->set( 'A', 'string' );
            $this->context->set( 'B', 10 );
            $this->context->set( 'C', true );

            $expected = [true, 'string', 10];
            $returned = $this->context->separate( 'C', 'A', 'B' );

            $this->assertEquals( $expected, $returned );
        } catch ( Exception $e ) {
            $this->fail( $e->getMessage() );
        }
    }

    public function testSeparateWithFormatIsGettingContext(): void {
        try {
            $this->context->set( 'A', 'string' );
            $this->context->set( 'B', 10 );
            $this->context->set( 'C', true );
            
            $this->context->format(function(mixed $v): mixed {
                if(is_bool($v)) {
                    return true === $v ? 'True' : 'False';
                }

                return is_string($v) ? $v : var_export($v, true);
            });

            $expected = ['True', 'string', '10'];
            $returned = $this->context->separate( 'C', 'A', 'B' );

            $this->assertEquals( $expected, $returned );
        } catch ( Exception $e ) {
            $this->fail( $e->getMessage() );
        }
    }
}
