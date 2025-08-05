<?php

declare(strict_types = 1);

namespace KeilielOliveira\PhpException\Collectors;

use KeilielOliveira\PhpException\Facades\Context;
use KeilielOliveira\PhpException\Facades\Handler;

class ContextCollector {
    private string $marker;

    public function __construct( string $marker ) {
        $this->marker = $marker;
    }

    public function collect(): string {
        if ( $this->hasIndex() ) {
            $index = $this->getIndex();
            $context = Handler::get( $index );

            $key = $this->getKey();
            $value = $context->get( $key );

            return is_string( $value ) ? $value : var_export( $value, true );
        }

        $value = Context::get( $this->marker );

        return is_string( $value ) ? $value : var_export( $value, true );
    }

    private function hasIndex(): bool {
        $pattern = '/\[.+\]/';

        return preg_match( $pattern, $this->marker ) ? true : false;
    }

    private function getIndex(): string {
        $pattern = '/\[(.+)\]/';
        preg_match( $pattern, $this->marker, $matches );

        return $matches[1];
    }

    private function getKey(): string {
        $pattern = '/(\[.+\])?([^\{\}\[\]]+)(\[.+\])?/';
        preg_match( $pattern, $this->marker, $matches );

        return $matches[2];
    }
}
