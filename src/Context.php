<?php

declare(strict_types = 1);

namespace KeilielOliveira\PhpException;

use KeilielOliveira\PhpException\Validators\CallbackValidator;
use KeilielOliveira\PhpException\Validators\ContextValidator;

/**
 * Controla o fluxo de dados de contexto de exceções.
 */
class Context {
    /** @var mixed[] Armazena o contexto das exceções */
    private array $context = [];

    private bool $auth = true;

    private \Closure $format;

    /**
     * Defini a chave com o valor dentro do contexto atual.
     *
     * Uma mesma chave não pode ser usada para dois valores, fazer isso lançaria uma exceção.
     */
    public function set( string $key, mixed $value ): void {
        if ( !$this->auth ) {
            $this->auth = true;

            return;
        }
        new ContextValidator( $this, $key )->validateIfNotHasContext();
        $this->context[$key] = $value;
    }

    /**
     * Defini a chave com o valor dentro do contexto atual.
     *
     * Ao contrario do método set(), este método ignora se a chave já foi usada, ele é útil para valores que
     * são atualizados constantemente como valores de um array sendo percorrido.
     */
    public function forceSet( string $key, mixed $value ): void {
        if ( !$this->auth ) {
            $this->auth = true;

            return;
        }
        $this->context[$key] = $value;
    }

    /**
     * Atualiza o valor da chave dentro do contexto atual.
     *
     * Caso a chave não tenha sido definida anteriormente irá lançar uma exceção.
     */
    public function update( string $key, mixed $value ): void {
        if ( !$this->auth ) {
            $this->auth = true;

            return;
        }
        new ContextValidator( $this, $key )->validateIfHasContext();
        $this->forceSet( $key, $value );
    }

    /**
     * Deleta as chaves e seus valores dentro do contexto atual.
     *
     * Caso uma das chaves não exista, uma exceção será lançada.
     */
    public function delete( string ...$keys ): void {
        if ( !$this->auth ) {
            $this->auth = true;

            return;
        }
        foreach ( $keys as $i => $key ) {
            new ContextValidator( $this, $key )->validateIfHasContext();
            unset( $this->context[$key] );
        }
    }

    /**
     * Limpa os dados armazenados no contexto atual.
     *
     * Se for executado com o valor padrão (null), irá deletar todos os dados do contexto atual.
     * Se uma função for passada, ele receberá todos os dados do contexto atual, e os dados retornados
     * por ela no formato de um único array serão mantidos.
     *
     * Se a função de callback não for uma função ou não receber um único parâmetro do tipo mixed
     * uma exceção será
     */
    public function clear( ?callable $callback = null ): void {
        if ( !$this->auth ) {
            $this->auth = true;

            return;
        }
        new CallbackValidator( $callback, 'array', 'array' );

        if ( null !== $callback ) {
            $context = $callback( $this->context );
            $this->context = is_array( $context ) ? $context : [$context];

            return;
        }

        $this->context = [];
    }

    /**
     * Verifica se uma chave existe dentro do contexto atual.
     */
    public function has( string $key ): bool {
        return in_array( $key, array_keys( $this->context ) );
    }

    /**
     * Retorna o valor da chave no contexto atual.
     *
     * Permite a passagem de uma função de callback que irá receber o valor da chave, o valor retornado será
     * o valor retornado pela função de callback.
     *
     * Se a chave não existir, uma exceção será lançada.
     * Se a função de callback não for uma função ou não receber um único parâmetro do tipo mixed
     * uma exceção será
     */
    public function get( string $key, ?callable $callback = null ): mixed {
        if ( !$this->auth ) {
            $this->auth = true;

            return null;
        }
        new ContextValidator( $this, $key )->validateIfHasContext();
        new CallbackValidator( $callback );

        $context = $this->context[$key];
        if ( null !== $callback ) {
            return $callback( $context );
        }

        return $context;
    }

    /**
     * Retorna todos os dados do contexto atual.
     *
     * Permiti a passagem de uma função de callback que receberá todos os dados do contexto, o retorno do
     * método será o retorno da função de callback.
     *
     * Se a função de callback não for uma função ou não receber um único parâmetro do tipo mixed
     * uma exceção será
     *
     * @return ?mixed[]
     */
    public function getAll( ?callable $callback = null ): ?array {
        if ( !$this->auth ) {
            $this->auth = true;

            return null;
        }
        new CallbackValidator( $callback, 'array', 'array' );

        if ( null !== $callback ) {
            $response = $callback( $this->context );

            return is_array( $response ) ? $response : [$response];
        }

        return $this->context;
    }

    /**
     * Faz com que o próximo método só seja executado caso a chave recebida exista.
     */
    public function ifHas( string $key ): self {
        $this->auth = $this->has( $key );

        return $this;
    }

    /**
     * Faz com que o próximo método só seja executado caso a chave recebida não exista.
     */
    public function ifNotHas( string $key ): self {
        $this->auth = !$this->has( $key );

        return $this;
    }

    /**
     * Faz com que o próximo método só seja executado quando a função de callback retorne TRUE.
     *
     * A função de callback deve retornar bool e receber um parâmetro do tipo
     * KeilielOliveira\PhpException\Context, caso contrario uma exceção será lançada.
     *
     * O parâmetro da função de callback irá receber a instancia atual do contexto.
     */
    public function when( callable $callback ): self {
        new CallbackValidator( $callback, 'bool', self::class );
        $response = $callback( $this );
        $this->auth = is_bool( $response ) ? $response : false;

        return $this;
    }

    /**
     * Separa as chaves recebidas do contexto e retorna em um array.
     *
     * Este método utiliza o método get() para recuperar cada chave, portanto, se uma chave não existir
     * a exceção do método get() será lançada.
     *
     * @return mixed[]
     */
    public function separate( string ...$keys ): array {
        $preparedContext = [];

        foreach ( $keys as $i => $key ) {
            $value = $this->get( $key );
            $callback = $this->format ?? false;
            $preparedContext[] = false === $callback ? $value : $callback( $value );
        }

        return $preparedContext;
    }

    /**
     * Salva uma função de callback que será usada para todos os valores do método separate().
     *
     * Este método visa facilitar a formatação dos valores do separate(), a função será mantida e só será
     * substituída quando uma nova função de callback for passada.
     *
     * A função de callback espera receber um parâmetro do tipo "mixed" e deve retornar "mixed", caso esses
     * critérios não sejam atendidos, uma exceção será lançada.
     */
    public function format( callable $callback ): self {
        new CallbackValidator( $callback );
        $this->format = \Closure::fromCallable( $callback );

        return $this;
    }
}
