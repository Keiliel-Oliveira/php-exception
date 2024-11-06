<?php

declare( strict_types = 1 );

namespace PhpException;

class Exception extends \Exception {
    protected array $errorData = [];

    public function __construct( string $message, int $code = 0, ?\Throwable $previous = null ) {
        parent::__construct( $message, $code, $previous );

        if ( $this->validate() ) {
            $this->prepareException();
        }
    }

    public function setErrorType( string $type ): void {
        $this->errorData['error_type'] = $type;
    }

    public function addPossibleErrorCause( string $cause ): void {
        $this->errorData['error_possible_causes'][] = $cause;
    }

    public function setExceptionTemplate( string $template ): void {
        $regexp = '/\.php$/';

        if ( !file_exists( $template ) || !preg_match( $regexp, $template ) ) {
            $exception = new Exception(
                "O caminho <b>{$template}</b>, não corresponde a um arquivo php de template valido.",
                100
            );
            $exception->setErrorType( 'Arquivo de template invalido.' );

            throw $exception;
        }

        $this->errorData['exception_template'] = $template;
    }

    public function printException(): void {
        if ( $this->validate() ) {
            $errorData = $this->errorData;

            require_once $this->errorData['exception_template'];
        }
    }

    private function validate(): bool {
        if ( !defined( 'IGNORE_UNDEFINED_CONSTANT_USE_PHP_EXCEPTION_CLASS' ) ) {
            $this->hasConstant();

            return $this->useClass();
        }

        if ( IGNORE_UNDEFINED_CONSTANT_USE_PHP_EXCEPTION_CLASS ) {
            return true;
        }
    }

    private function hasConstant(): true {
        if ( !defined( 'USE_PHP_EXCEPTION_CLASS' ) ) {
            $this->defineExceptionTempConst();
            $exception = new self(
                'A constante USE_PHP_EXCEPTION_CLASS não foi definida.',
                104
            );
            $exception->setErrorType( 'Constante não definida' );
            $exception->addPossibleErrorCause( 'A constante não foi definida, ela deve ser definida no escopo global como <b>true</b> para exibir as <b>Exceptions</b> e <b>false</b> para oculta-las.' );

            throw $exception;
        }

        return true;
    }

    private function useClass(): bool {
        if ( !USE_PHP_EXCEPTION_CLASS ) {
            return false;
        }

        return true;
    }

    private function defineExceptionTempConst() {
        define( 'IGNORE_UNDEFINED_CONSTANT_USE_PHP_EXCEPTION_CLASS', true );
    }

    private function prepareException(): void {
        $this->errorData = array_merge(
            $this->errorData,
            [
                'exception_template' => __DIR__ . '/templates/default.php',
                'error_type' => 'Undefined',
                'error_possible_causes' => [],
                'error_message' => $this->getMessage(),
                'error_data' => $this->getExceptionData(),
                'error_code_snippet' => $this->getErrorCodeSnippet(
                    $this->getFile(),
                    $this->getLine()
                ),
                'error_origin_code_snippet' => $this->getErrorOriginCodeSnippet(),
            ]
        );
    }

    private function getExceptionData(): array {
        $trace = $this->getTrace();
        $trace = isset( $trace[0] ) ? $trace[0] : ['function' => false];

        return [
            'code' => $this->getCode(),
            'filename' => $this->getFile(),
            'line' => $this->getLine(),
            'class' => isset( $trace['class'] ) ? $trace['class'] : false,
            'method' => isset( $trace['class'] ) ? $trace['function'] : false,
            'function' => !isset( $trace['class'] ) ? $trace['function'] : false,
        ];
    }

    private function getErrorCodeSnippet( string $filename, int $fileLine ): string {
        $fileContent = file( $filename );
        $fileLine = $fileLine - 1;
        $fileData = "<?php\n";

        foreach ( $fileContent as $index => $line ) {
            if ( $index >= $fileLine - 5 && $index <= $fileLine + 5 ) {
                $fileData .= $line;
            }
        }

        return $fileData . "\n?>";
    }

    private function getErrorOriginCodeSnippet(): false|string {
        $trace = $this->getTrace();

        if ( isset( $trace[0] ) ) {
            $trace = array_shift( $trace );

            return $this->getErrorCodeSnippet(
                $trace['file'],
                $trace['line']
            );
        }

        return false;
    }
}
