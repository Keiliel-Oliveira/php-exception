<?php

declare(strict_types = 1);

namespace KeilielOliveira\PhpException\Validators;

use KeilielOliveira\PhpException\Exception\ExceptionPrepare;
use ReflectionNamedType;

/**
 * Valida as funções de callback da classe Context.
 *
 * @see\KeilielOliveira\PhpException\Context
 */
class CallbackValidator {
    public function __construct( ?callable $callback ) {
        if ( is_callable( $callback ) ) {
            $callback = \Closure::fromCallable( $callback );
            $callback = new \ReflectionFunction( $callback );

            $this->hasReturnType( $callback );
            $returnType = $callback->getReturnType();
            $this->isNull( $returnType );
            $this->hasSingleReturnType( $returnType );
            $this->returnMixed( $returnType );

            $this->hasParam( $callback );

            $param = $callback->getParameters();
            $param = array_shift( $param );
            $this->isNull( $param );

            $this->hasType( $param );

            $paramType = $param->getType();
            $paramName = $param->getName();
            $this->isNull( $paramType );

            $this->hasSingleType( $paramName, $paramType );
            $this->hasMixedType( $paramName, $paramType );
        }
    }

    private function hasReturnType( \ReflectionFunction $callback ): void {
        if ( !$callback->hasReturnType() ) {
            $prepare = new ExceptionPrepare( 'função sem tipo de retorno', 'A função de callback deve retornar "mixed".' );

            throw new \Exception( $prepare->getMessage() );
        }
    }

    /**
     * @phpstan-assert ReflectionNamedType $returnType
     *
     * @throws \Exception
     */
    private function hasSingleReturnType( \ReflectionType $returnType ): void {
        if ( !$returnType instanceof \ReflectionNamedType ) {
            $prepare = new ExceptionPrepare( 'tipo de retorno invalido', 'A função de callback deve retornar "mixed", mas está retornando múltiplos tipos.' );

            throw new \Exception( $prepare->getMessage() );
        }
    }

    private function returnMixed( \ReflectionNamedType $returnType ): void {
        if ( 'mixed' != $returnType->getName() ) {
            $prepare = new ExceptionPrepare( 'função sem tipo de retorno', sprintf( 'A função de callback deve retornar "mixed" mas está retornando "%s".', $returnType->getName() ) );

            throw new \Exception( $prepare->getMessage() );
        }
    }

    private function hasParam( \ReflectionFunction $callback ): void {
        if ( 1 != $callback->getNumberOfParameters() ) {
            $prepare = new ExceptionPrepare( 'número de parâmetros excede o esperado', sprintf( 'O número de parâmetros que a função de callback espera receber é somente 1 mas "%s" foram passados.', $callback->getNumberOfParameters() ) );

            throw new \Exception( $prepare->getMessage() );
        }
    }

    private function hasType( \ReflectionParameter $param ): void {
        if ( !$param->hasType() ) {
            $prepare = new ExceptionPrepare( 'parâmetro com tipo invalido', sprintf( 'A função de callback espera receber um parâmetro do tipo "mixed", mas "%s" não possui um tipo definido.', $param->getName() ) );

            throw new \Exception( $prepare->getMessage() );
        }
    }

    /**
     * @phpstan-assert ReflectionNamedType $paramType
     *
     * @throws \Exception
     */
    private function hasSingleType( string $paramName, \ReflectionType $paramType ): void {
        if ( !$paramType instanceof \ReflectionNamedType ) {
            $prepare = new ExceptionPrepare( 'parâmetro com tipo invalido', sprintf( 'A função de callback espera receber um parâmetro do tipo "mixed", mas "%s" possui múltiplos tipos.', $paramName ) );

            throw new \Exception( $prepare->getMessage() );
        }
    }

    private function hasMixedType( string $paramName, \ReflectionNamedType $paramType ): void {
        if ( 'mixed' !== $paramType->getName() ) {
            $prepare = new ExceptionPrepare( 'parâmetro com tipo invalido', sprintf( 'A função de callback espera receber um parâmetro do tipo "mixed", mas "%s" é do tipo "%s".', $paramName, $paramType->getName() ) );

            throw new \Exception( $prepare->getMessage() );
        }
    }

    /**
     * @phpstan-assert !null $var
     */
    private function isNull( mixed $var ): void {
        if ( null === $var ) {
            throw new \Exception( 'Ocorreu um erro inesperado.' );
        }
    }
}
