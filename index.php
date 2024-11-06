<?php

require_once 'vendor/autoload.php';

define( 'USE_PHP_EXCEPTION_CLASS', true );

try {
    $e = new PhpException\Exception('Exception message', 100);
    $e->setErrorType('Undefined Error');
    $e->addPossibleErrorCause('Undefined Cause');
    throw $e;
}catch(PhpException\Exception $e) {
    $e->printException();
}