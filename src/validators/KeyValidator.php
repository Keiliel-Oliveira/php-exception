<?php

declare(strict_types = 1);

namespace KeilielOliveira\PhpException\Validators;

use KeilielOliveira\PhpException\Context;
use KeilielOliveira\PhpException\Exception\ExceptionPrepare;
use KeilielOliveira\PhpException\Handler;

/**
 * Valida a chave de contexto recebida.
 */
class KeyValidator {
    private Context|Handler $exception;

    private string $key;

    public function __construct( Context|Handler $exception, string $key ) {
        $this->exception = $exception;
        $this->key = $key;

        if ( $exception instanceof Context ) {
            $this->validateIfContextKeyHasValidSyntax();
        } else {
            $this->validateIfContextNameHasValidSyntax();
        }
    }

    public function validateIfNotHasContext(): void {
        if ( $this->exception->has( $this->key ) ) {
            $prepare = new ExceptionPrepare( 'contexto invalido', sprintf( 'A chave "%s" já foi usada para definir um contexto anteriormente.', $this->key ) );

            throw new \Exception( $prepare->getMessage() );
        }
    }

    public function validateIfHasContext(): void {
        if ( !$this->exception->has( $this->key ) ) {
            $prepare = new ExceptionPrepare( 'contexto inexistente', sprintf( 'A chave de contexto "%s" não foi definida.', $this->key ) );

            throw new \Exception( $prepare->getMessage() );
        }
    }

    private function validateIfContextKeyHasValidSyntax(): void {
        $pattern = '[\{\}\[\]]';
        if ( preg_match( $pattern, $this->key ) ) {
            $prepare = new ExceptionPrepare( 'Sintaxe de chave invalida', sprintf( 'A chave "%s" possui uma sintaxe invalida, as chaves não podem conter "{}" ou "[]".', $this->key ) );

            throw new \Exception( $prepare->getMessage() );
        }
    }

    private function validateIfContextNameHasValidSyntax(): void {
        $pattern = '[\{\}\[\]]';
        if ( preg_match( $pattern, $this->key ) ) {
            $prepare = new ExceptionPrepare( 'Sintaxe de nome invalida', sprintf( 'O nome "%s" possui uma sintaxe invalida, os nomes não podem conter "{}" ou "[]".', $this->key ) );

            throw new \Exception( $prepare->getMessage() );
        }
    }
}
