<?php

declare(strict_types = 1);

namespace KeilielOliveira\PhpException\Validators;

use KeilielOliveira\PhpException\Context;
use KeilielOliveira\PhpException\Exception\ExceptionPrepare;

/**
 * Valida a chave de contexto recebida.
 */
class ContextValidator {
    private Context $exception;

    private string $key;

    public function __construct( Context $exception, string $key ) {
        $this->exception = $exception;
        $this->key = $key;
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
}
