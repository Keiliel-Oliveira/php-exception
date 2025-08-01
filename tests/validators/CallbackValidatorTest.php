<?php

declare(strict_types = 1);
use KeilielOliveira\PhpException\Context;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class CallbackValidatorTest extends TestCase {
    private Context $context;

    public function setUp(): void {
        $this->context = new Context();
        $this->context->set( 'A', true );
    }

    public function testIsValidatingIfCallbackHasReturnType(): void {
        try {
            $this->context->get( 'A', function () {} );
            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( Exception $e ) {
            $expected = 'hasReturnType';
            $returned = $e->getTrace()[0]['function'];

            $this->assertEquals( $expected, $returned );
        }
    }

    public function testIsValidatingIfCallbackHasSingleReturnType(): void {
        try {
            function testOne(): int|string {
                return '';
            };
            $this->context->get( 'A', 'testOne' );
            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( Exception $e ) {
            $expected = 'hasSingleReturnType';
            $returned = $e->getTrace()[0]['function'];

            $this->assertEquals( $expected, $returned );
        }
    }

    public function testIsValidatingIfCallbackReturnMixed(): void {
        try {
            function testTwo(): string {
                return '';
            };
            $this->context->get( 'A', 'testTwo' );
            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( Exception $e ) {
            $expected = 'returnMixed';
            $returned = $e->getTrace()[0]['function'];

            $this->assertEquals( $expected, $returned );
        }
    }

    public function testIsValidatingIfCallbackHasParam(): void {
        try {
            $this->context->get( 'A', function (): mixed {return ''; } );
            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( Exception $e ) {
            $expected = 'hasParam';
            $returned = $e->getTrace()[0]['function'];

            $this->assertEquals( $expected, $returned );
        }
    }

    public function testIsValidatingIfCallbackParamHasType(): void {
        try {
            $this->context->get( 'A', function ( $a ): mixed {return ''; } );
            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( Exception $e ) {
            $expected = 'hasType';
            $returned = $e->getTrace()[0]['function'];

            $this->assertEquals( $expected, $returned );
        }
    }

    public function testIsValidatingIfCallbackParamHasSingleType(): void {
        try {
            $this->context->get( 'A', function ( int|string $a ): mixed {return ''; } );
            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( Exception $e ) {
            $expected = 'hasSingleType';
            $returned = $e->getTrace()[0]['function'];

            $this->assertEquals( $expected, $returned );
        }
    }

    public function testIsValidatingIfCallbackParamHasMixedType(): void {
        try {
            $this->context->get( 'A', function ( string $a ): mixed {return ''; } );
            $this->fail( 'Nenhuma exceção foi lançada.' );
        } catch ( Exception $e ) {
            $expected = 'hasMixedType';
            $returned = $e->getTrace()[0]['function'];

            $this->assertEquals( $expected, $returned );
        }
    }
}
