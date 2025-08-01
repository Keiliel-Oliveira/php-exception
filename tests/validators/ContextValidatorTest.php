<?php

declare(strict_types = 1);
use KeilielOliveira\PhpException\Context;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ContextValidatorTest extends TestCase {
    private Context $context;

    public function setUp(): void {
        $context = new Context();
        $context->set( 'A', true );
        $this->context = $context;
    }

    public function testValidateIfNotHasContextIsValidating(): void {
        try {
            $this->context->set( 'A', false );
            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( Exception $e ) {
            $expected = 'validateIfNotHasContext';
            $returned = $e->getTrace()[0]['function'];

            $this->assertEquals( $expected, $returned );
        }
    }

    public function testValidateIfHasContextIsValidating(): void {
        try {
            $this->context->get( 'B' );
            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( Exception $e ) {
            $expected = 'validateIfHasContext';
            $returned = $e->getTrace()[0]['function'];

            $this->assertEquals( $expected, $returned );
        }
    }
}
