<?php

declare(strict_types = 1);

namespace KeilielOliveira\PhpException;

use Exception as VanillaException;
use KeilielOliveira\PhpException\Collectors\ContextCollector;
use KeilielOliveira\PhpException\Collectors\TemplateMarkersCollector;

/**
 * Classe de exceção base com sistema de conversão de marcações em mensagens por valores.
 */
class Exception extends VanillaException {
    /**
     * Salva os dados da exceção sendo lançada dentro da Exception classe do php.
     *
     * A mensagem da exceção pode possuir marcações que serão substituídas pelos seus respectivos valores
     * no contexto atual e contextos salvos anteriormente.
     *
     * Os contextos usados serão somente os gerenciados através de suas respectivas interfaces estáticas.
     */
    public function __construct( string $message = '', int $code = 0, ?\Throwable $previous = null ) {
        $message = $this->prepareMessage( $message );
        parent::__construct( $message, $code, $previous );
    }

    private function prepareMessage( string $message ): string {
        $collector = new TemplateMarkersCollector( $message );
        $markers = $collector->collect();

        foreach ( $markers as $key => $marker ) {
            $collector = new ContextCollector( $marker );
            $value = $collector->collect();

            $pattern = '/' . preg_quote( "{{$marker}}" ) . '/';
            $message = preg_replace( $pattern, $value, $message );

            if ( !is_string( $message ) ) {
                throw new Exception( 'Ocorreu um erro ao preparar a mensagem.' );
            }
        }

        return $message;
    }
}
