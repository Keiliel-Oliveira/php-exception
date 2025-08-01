<?php

declare(strict_types = 1);

namespace KeilielOliveira\PhpException\Exception;

use KeilielOliveira\PhpException\Context;

/**
 * Coleta dados extras no backtrace para as exceções.
 * 
 * @phpstan-type Backtrace array{
 *      file?: string,
 *      line?: int,
 *      type?: '->'|'::',
 *      args?: mixed[],
 *      class?: class-string,
 *      object?: object,
 *      function?: string
 * }
 * 
 * @phpstan-type AdditionalData array{
 *      file: string|false,
 *      line: int|false,
 *      args: mixed[]|false,
 *      class: class-string|false,
 *      method: string|false
 * }
 */
class DataCollector {
    /**
     * @return AdditionalData
     */
    public function collect(): array {
        $backtrace = $this->getBacktrace();

        return $this->getData( $backtrace );
    }

    /**
     * @return Backtrace
     */
    private function getBacktrace(): array {
        $backtrace = debug_backtrace();
        foreach ( $backtrace as $key => $trace ) {
            if ( isset( $trace['class'] ) && Context::class == $trace['class'] ) {
                break;
            }

            unset( $backtrace[$key] );
        }

        if(empty($backtrace)) {
            throw new \Exception('Não foi possível recuperar o backtrace.');
        }

        return array_shift( $backtrace );
    }

    /**
     * @param Backtrace $backtrace
     *
     * @return AdditionalData
     */
    private function getData( array $backtrace ): array {
        return [
            'file' => $backtrace['file'] ?? false,
            'line' => $backtrace['line'] ?? false,
            'class' => $backtrace['class'] ?? false,
            'method' => $backtrace['function'] ?? false,
            'args' => $backtrace['args'] ?? false,
        ];
    }
}
