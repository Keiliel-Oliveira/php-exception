<?php

declare(strict_types = 1);

namespace KeilielOliveira\PhpException\Collectors;

class TemplateMarkersCollector {
    private string $template;

    public function __construct( string $template ) {
        $this->template = $template;
    }

    /**
     * @return string[]
     */
    public function collect(): array {
        $pattern = '/\{([^\{\}]+)\}/';
        preg_match_all( $pattern, $this->template, $matches );

        return $matches[1];
    }
}
