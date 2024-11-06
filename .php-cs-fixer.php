<?php 

use PhpCsFixer\Finder as Finder;
use PhpCsFixer\Config as Config;

$finder = ( new Finder() )
    ->in( __DIR__ )
;

return ( new Config() )
    ->setRules( [
        '@PSR12' => true,
        '@PhpCsFixer' => true,
        'braces_position' => [
            'functions_opening_brace' => 'same_line',
            'classes_opening_brace' => 'same_line'
        ],
        'spaces_inside_parentheses' => [
            'space' => 'single',
        ],
        'concat_space' => [
            'spacing' => 'one',
        ],
        'cast_spaces' => [
            'space' => 'single',
        ],
        'declare_parentheses' => false,
        'function_declaration' => [
            'closure_fn_spacing' => 'none',
            'closure_function_spacing' => 'none',
        ],
        'declare_equal_normalize' => [
            'space' => 'single',
        ],
        'blank_line_before_statement' => [
            'statements' => ['break', 'case', 'continue', 'declare', 'default', 'do', 'exit', 'for', 'foreach', 'goto', 'if', 'include', 'include_once', 'phpdoc', 'require', 'require_once', 'return', 'switch', 'throw', 'try', 'while', 'yield', 'yield_from']
        ]
    ] )
    ->setFinder( $finder );
