<?php

declare(strict_types = 1);
use KeilielOliveira\PhpException\Context;
use KeilielOliveira\PhpException\Handler;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class HandlerTest extends TestCase {
    public function testSaveAndGetIsSavingAndReturningContextInstance(): void {
        try {
            $context = new Context();
            $context->set( 'key', true );

            $handler = new Handler();
            $handler->save( 'A', $context );
            $context = $handler->get( 'A' );

            $this->assertInstanceOf( Context::class, $context );
            $this->assertTrue( $context->get( 'key' ) );
        } catch ( Exception $e ) {
            $this->fail( $e->getMessage() );
        }
    }

    public function testUpdateIsUpdatingContextInstance(): void {
        try {
            $context = new Context();
            $handler = new Handler();
            $handler->save( 'A', $context );

            // Cria um novo contexto e atualiza.
            $context = new Context();
            $context->set( 'key', true );
            $handler->update( 'A', $context );

            $this->assertTrue( $handler->get( 'A' )->get( 'key' ) );
        } catch ( Exception $e ) {
            $this->fail( $e->getMessage() );
        }
    }

    public function testDeleteAndHasIsDeletingAndValidatingContextInstance(): void {
        try {
            $context = new Context();
            $handler = new Handler();
            $handler->save( 'A', $context );

            $this->assertTrue( $handler->has( 'A' ) );
            $handler->delete( 'A' );
            $this->assertFalse( $handler->has( 'A' ) );
        } catch ( Exception $e ) {
            $this->fail( $e->getMessage() );
        }
    }
}
