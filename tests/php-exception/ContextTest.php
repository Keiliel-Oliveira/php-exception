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

    public function testAddContextAndGetContextIsAddingAndReturningContext(): void {
        $this->context->set( 'key', true );
        $this->assertTrue( $this->context->get( 'key' ) );
    }

    public function testGetContextWithCallbackIsReturning(): void {
        $this->context->set( 'key', [1, 2, 3, 4] );
        $expected = [2, 4];
        $returned = $this->context->get( 'key', function ( mixed $context ): mixed {
            return array_values( array_filter( $context, function ( int $i ): bool {
                return 0 == $i % 2;
            } ) );
        } );

        $this->assertEquals( $expected, $returned );
    }

    public function testGetAllContextWithCallbackIsReturning(): void {
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

    public function testHasContextIsValidating(): void {
        $this->context->set( 'key', 'value' );

        $this->assertTrue( $this->context->has( 'key' ) );
    }

    public function testForceSetContextIsAutomaticallyReplacingContext(): void {
        $this->context->set( 'key', 'value' );

        $expected = 'new value';
        $this->context->forceSet( 'key', $expected );
        $returned = $this->context->get( 'key' );

        $this->assertEquals( $expected, $returned );
    }

    public function testRemoveContextIsRemovingContext(): void {
        $this->context->set( 'A', 'string' );
        $this->context->set( 'B', 10 );
        $this->context->set( 'C', true );

        $this->context->delete( 'A', 'C' );
        $expected = ['B' => 10];
        $returned = $this->context->getAll();

        $this->assertEquals( $expected, $returned );
    }

    public function testClearContextWithCallbackIsCleaningContext(): void {
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
}
