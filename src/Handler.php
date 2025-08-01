<?php

declare(strict_types = 1);

namespace KeilielOliveira\PhpException;

/**
 * Controla o ciclo de vidas das instancias dos contextos.
 */
class Handler {
    /** @var array<string, Context> */
    private array $instances = [];

    /**
     * Salva um contexto.
     *
     * Caso o contexto já exista, uma exceção sera lançada.
     *
     * @throws \Exception
     */
    public function save( string $name, Context $context ): void {
        if ( $this->has( $name ) ) {
            throw new \Exception( sprintf( 'O contexto "%s" já foi salvo.', $name ) );
        }

        $this->instances[$name] = $context;
    }

    /**
     * Substitui um contexto salvo por outro.
     *
     * Caso o contexto não exista, uma exceção sera lançada.
     *
     * @throws \Exception
     */
    public function update( string $name, Context $context ): void {
        if ( !$this->has( $name ) ) {
            throw new \Exception( sprintf( 'O contexto "%s" não foi definido.', $name ) );
        }

        $this->instances[$name] = $context;
    }

    /**
     * Deleta um contexto.
     *
     * Caso o contexto não exista, uma exceção sera lançada.
     *
     * @throws \Exception
     */
    public function delete( string $name ): void {
        if ( !$this->has( $name ) ) {
            throw new \Exception( sprintf( 'O contexto "%s" não foi definido.', $name ) );
        }

        unset( $this->instances[$name] );
    }

    /**
     * Recupera um contexto.
     *
     * Caso o contexto não exista, uma exceção sera lançada.
     *
     * @throws \Exception
     */
    public function get( string $name ): Context {
        if ( !$this->has( $name ) ) {
            throw new \Exception( sprintf( 'O contexto "%s" não foi definido.', $name ) );
        }

        return $this->instances[$name];
    }

    /**
     * Verifica se um contexto existe.
     */
    public function has( string $name ): bool {
        return isset( $this->instances[$name] );
    }
}
