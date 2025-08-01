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
    private string $returnType;

    private string $paramType;

    public function __construct( ?callable $callback, string $returnType = 'mixed', string $paramType = 'mixed' ) {
        if ( is_callable( $callback ) ) {
            $this->returnType = $returnType;
            $this->paramType = $paramType;
            $this->validate( $callback );
        }
    }

    private function validate( callable $callback ): void {
        $callback = \Closure::fromCallable( $callback );
        $callback = new \ReflectionFunction( $callback );

        $this->hasReturnType( $callback );
        $returnType = $callback->getReturnType();
        $this->isNull( $returnType );
        $this->hasSingleReturnType( $returnType );
        $this->returnExpectedType( $returnType );

        $this->hasParam( $callback );

        $param = $callback->getParameters();
        $param = array_shift( $param );
        $this->isNull( $param );

        $this->hasType( $param );

        $paramType = $param->getType();
        $paramName = $param->getName();
        $this->isNull( $paramType );

        $this->hasSingleType( $paramName, $paramType );
        $this->hasExpectedType( $paramName, $paramType );
    }

    private function hasReturnType( \ReflectionFunction $callback ): void {
        if ( !$callback->hasReturnType() ) {
            $prepare = new ExceptionPrepare( 'função sem tipo de retorno', sprintf( 'A função de callback deve retornar "%s".', $this->returnType ) );

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
            $prepare = new ExceptionPrepare( 'tipo de retorno invalido', sprintf( 'A função de callback deve retornar "%s", mas está retornando múltiplos tipos.', $this->returnType ) );

            throw new \Exception( $prepare->getMessage() );
        }
    }

    private function returnExpectedType( \ReflectionNamedType $returnType ): void {
        if ( $this->returnType != $returnType->getName() ) {
            $prepare = new ExceptionPrepare( 'função sem tipo de retorno', sprintf( 'A função de callback deve retornar "%s" mas está retornando "%s".', $this->returnType, $returnType->getName() ) );

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
            $prepare = new ExceptionPrepare( 'parâmetro com tipo invalido', sprintf( 'A função de callback espera receber um parâmetro do tipo "%s", mas "%s" não possui um tipo definido.', $this->paramType, $param->getName() ) );

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
            $prepare = new ExceptionPrepare( 'parâmetro com tipo invalido', sprintf( 'A função de callback espera receber um parâmetro do tipo "%s", mas "%s" possui múltiplos tipos.', $this->paramType, $paramName ) );

            throw new \Exception( $prepare->getMessage() );
        }
    }

    private function hasExpectedType( string $paramName, \ReflectionNamedType $paramType ): void {
        if ( $this->paramType !== $paramType->getName() ) {
            $prepare = new ExceptionPrepare( 'parâmetro com tipo invalido', sprintf( 'A função de callback espera receber um parâmetro do tipo "%s", mas "%s" é do tipo "%s".', $this->paramType, $paramName, $paramType->getName() ) );

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
