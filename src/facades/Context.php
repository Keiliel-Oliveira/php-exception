<?php

declare(strict_types = 1);

namespace KeilielOliveira\PhpException\Facades;

use KeilielOliveira\PhpException\Context as MainContext;

/**
 * Simplificação estática da classe principal KeilielOliveira\PhpException\Context.
 */
class Context {
    private static ?MainContext $context = null;

    /**
     * Inicia uma nova instancia de contexto.
     */
    public static function newContext(): void {
        self::$context = null;
    }

    /**
     * Retorna a instancia do contexto atual.
     *
     * Se não houver uma instancia, uma nova será criada e retornada, mas não salva internamente.
     */
    public static function getContext(): MainContext {
        return self::$context ?? new MainContext();
    }

    /**
     * Defini a chave com o valor dentro do contexto atual.
     *
     * Uma mesma chave não pode ser usada para dois valores, fazer isso lançaria uma exceção.
     */
    public static function set( string $key, mixed $value ): void {
        self::getContextInstance()->set( $key, $value );
    }

    /**
     * Defini a chave com o valor dentro do contexto atual.
     *
     * Ao contrario do método set(), este método ignora se a chave já foi usada, ele é útil para valores que
     * são atualizados constantemente como valores de um array sendo percorrido.
     */
    public static function forceSet( string $key, mixed $value ): void {
        self::getContextInstance()->forceSet( $key, $value );
    }

    /**
     * Atualiza o valor da chave dentro do contexto atual.
     *
     * Caso a chave não tenha sido definida anteriormente irá lançar uma exceção.
     */
    public static function update( string $key, mixed $value ): void {
        self::getContextInstance()->update( $key, $value );
    }

    /**
     * Deleta as chaves e seus valores dentro do contexto atual.
     *
     * Caso uma das chaves não exista, uma exceção será lançada.
     */
    public static function delete( string ...$keys ): void {
        self::getContextInstance()->delete( ...$keys );
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
    public static function clear( ?callable $callback = null ): void {
        self::getContextInstance()->clear( $callback );
    }

    /**
     * Verifica se uma chave existe dentro do contexto atual.
     */
    public static function has( string $key ): bool {
        return self::getContextInstance()->has( $key );
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
    public static function get( string $key, ?callable $callback = null ): mixed {
        return self::getContextInstance()->get( $key, $callback );
    }

    /**
     * Retorna todos os dados do contexto atual.
     *
     * Permiti a passagem de uma função de callback que receberá todos os dados do contexto, o retorno do
     * método será o retorno da função de callback.
     *
     * Se a função de callback não for uma função ou não receber um único parâmetro do tipo mixed
     * uma exceção será
     */
    public static function getAll( ?callable $callback = null ): mixed {
        return self::getContextInstance()->getAll( $callback );
    }

    /**
     * Faz com que o próximo método só seja executado caso a chave recebida exista.
     */
    public static function ifHas( string $key ): self {
        self::getContextInstance()->ifHas( $key );

        return new self();
    }

    /**
     * Faz com que o próximo método só seja executado caso a chave recebida não exista.
     */
    public static function ifNotHas( string $key ): self {
        self::getContextInstance()->ifNotHas( $key );

        return new self();
    }

    /**
     * Faz com que o próximo método só seja executado quando a função de callback retorne TRUE.
     *
     * A função de callback deve retornar bool e receber um parâmetro do tipo
     * KeilielOliveira\PhpException\Context, caso contrario uma exceção será lançada.
     *
     * O parâmetro da função de callback irá receber a instancia atual do contexto.
     */
    public static function when( callable $callback ): self {
        self::getContextInstance()->when( $callback );

        return new self();
    }

    /**
     * Separa as chaves recebidas do contexto e retorna em um array.
     *
     * Este método utiliza o método get() para recuperar cada chave, portanto, se uma chave não existir
     * a exceção do método get() será lançada.
     *
     * @return mixed[]
     */
    public static function separate( string ...$keys ): array {
        return self::getContextInstance()->separate( ...$keys );
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
    public static function format( callable $callback ): self {
        self::getContextInstance()->format( $callback );

        return new self();
    }

    private static function getContextInstance(): MainContext {
        if ( !isset( self::$context ) ) {
            self::$context = new MainContext();
        }

        return self::$context;
    }
}
