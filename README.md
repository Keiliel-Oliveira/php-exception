# PHP Exception

Essa é uma biblioteca que visa o simplificar a geração de exceções mais descritivas através da manipulação de contextos.

# Uso simples

Um pequeno exemplo de uso.

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

try {
    Context::set( 'error_type', 'erro inesperado' );

    throw new Exception( sprintf( 'Erro: %s', ...Context::separate( 'error_type' ) ) );
} catch ( Exception $e ) {
    echo $e->getMessage();
}
```

Neste exemplo, o contexto *'error_type'* é salvo para recuperação posterior, no exemplo ele é recuperado com o método *separate()* e usado em um *sprintf()*.

# Documentação

A biblioteca embora simples, possui diversos métodos para auxiliar na geração de exceções mais descritivas, abaixo segue-se uma lista das documentações.

Mas antes, um adendo relevante, a biblioteca possui dois tipos de identificadores únicos, cada qual com sua finalidade, mas algo que se aplica a ambos é a sintaxe, ela possui somente duas regras simples, não pode conter *{}* (chaves) nem *[]* (colchetes).

- [Manipulação de contexto](./docs/contexto.md)
- [Manipulação de instancias de contexto](./docs/instancia_de_contextos.md)
- [Classe de exceções](./docs/classe_de_exceções.md)