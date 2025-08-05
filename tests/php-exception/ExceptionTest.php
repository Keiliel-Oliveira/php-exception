<?php

declare(strict_types = 1);
use KeilielOliveira\PhpException\Exception;
use KeilielOliveira\PhpException\Facades\Context;
use KeilielOliveira\PhpException\Facades\Handler;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ExceptionTest extends TestCase {
    public function setUp(): void {
        Context::set( 'key', 1 );
        Handler::save( 'A', Context::getContext() );

        Context::newContext();
        Context::set( 'key', 2 );
    }

    public function testExceptionIsGeneratingMessage(): void {
        $this->expectExceptionMessage( '121' );

        throw new Exception( '{[A]key}{key}{key[A]}' );
    }
}
