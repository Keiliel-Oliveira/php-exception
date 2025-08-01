<?php

declare(strict_types = 1);

namespace KeilielOliveira\PhpException\Exception;

class ExceptionPrepare {
    private string $message;

    public function __construct( string $type, string $message ) {
        $additional = $this->prepareAdditionalData();
        $this->message = sprintf( 'Erro - %s: %s<br><br>%s', $type, $message, $additional );
    }

    public function getMessage(): string {
        return $this->message;
    }

    private function prepareAdditionalData(): string {
        $collector = new DataCollector();
        $backtrace = $collector->collect();

        $additional = '';
        foreach ( $backtrace as $key => $value ) {
            if ( false === $value ) {
                continue;
            }

            $value = is_array( $value ) ? var_export( $value, true ) : $value;
            $additional .= sprintf( '%s: %s<br>', $key, $value );
        }

        return $additional;
    }
}
