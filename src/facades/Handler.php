<?php

declare(strict_types = 1);

namespace KeilielOliveira\PhpException\Facades;

use KeilielOliveira\PhpException\Context;
use KeilielOliveira\PhpException\Handler as MainHandler;

class Handler {
    private static MainHandler $handler;

    /**
     * Salva um contexto.
     *
     * Caso o contexto já exista, uma exceção sera lançada.
     *
     * @throws \Exception
     */
    public static function save( string $name, Context $context ): void {
        self::getHandlerInstance()->save( $name, $context );
    }

    /**
     * Substitui um contexto salvo por outro.
     *
     * Caso o contexto não exista, uma exceção sera lançada.
     *
     * @throws \Exception
     */
    public static function update( string $name, Context $context ): void {
        self::getHandlerInstance()->update( $name, $context );
    }

    /**
     * Deleta um contexto.
     *
     * Caso o contexto não exista, uma exceção sera lançada.
     *
     * @throws \Exception
     */
    public static function delete( string $name ): void {
        self::getHandlerInstance()->delete( $name );
    }

    /**
     * Recupera um contexto.
     *
     * Caso o contexto não exista, uma exceção sera lançada.
     *
     * @throws \Exception
     */
    public static function get( string $name ): Context {
        return self::getHandlerInstance()->get( $name );
    }

    /**
     * Verifica se um contexto existe.
     */
    public static function has( string $name ): bool {
        return self::getHandlerInstance()->has( $name );
    }

    private static function getHandlerInstance(): MainHandler {
        if ( !isset( self::$handler ) ) {
            self::$handler = new MainHandler();
        }

        return self::$handler;
    }
}
