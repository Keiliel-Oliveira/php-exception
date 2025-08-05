# Classe de exceções

Essa classe serve como uma classe de exceções genéricas ou como base para ser estendida por outras classes, ela mesmo estende a classe ```Exception```do php, o único diferencial está em sua funcionalidade que será explicada a seguir.

# Conversão de templates

Essa classe pode receber um tipo especifico de mensagens, mensagens com marcações que serão automaticamente substituídas pelos seus respectivos valores no contexto.

Uma observação importante a se fazer é que essa funcionalidade só está disponível para uso caso esteja usando as interfaces estáticas da biblioteca.

## Sintaxe das marcações

As marcações seguem uma sintaxe simples, elas são declaradas com o uso de {} (chaves), o conteúdo dentro delas será usado para recuperar o contexto esperado.

### exemplos:

```php
<?php

// Chaves simples buscadas dentro do contexto atual.
$template = '{key_a}, {key_b}';

// Chaves complexas buscadas dentro dos contextos salvos.
$template = '{[A]key_a}, {key_b[A]}';

```

A string dentro das {} (chaves) serão usadas como chaves em uma busca no contexto atual, um ponto importante é que essa busca é feita pelas funcionalidades das classes ```KeilielOliveira\PhpException\Facades\Context``` e ```KeilielOliveira\PhpException\Facades\Handler```, portanto, todas as possíveis causas de erros dentro desses métodos, como chaves invalidas, estarão ativas.

Outra parte importante da sintaxe é a possibilidade de especificar contextos salvos, pode ocorrer de algum dado especifico de um determinado contexto salvo ser necessário, para estes casos, especifique o nome do contexto dentro de [] (colchetes) no começo ou final da marcação, a ordem é irrelevante, isso fará com que primeiro, seja verificado se este contexto existe, e caso exista, irá buscar a chave dentro dele, novamente, todos os possíveis erros das classes principais podem ser acusados durante esse processo.

### exemplos de usos:

```php
<?php

use KeilielOliveira\PhpException\Exception;
use KeilielOliveira\PhpException\Facades\Context;
use KeilielOliveira\PhpException\Facades\Handler;

require_once 'vendor/autoload.php';

try {
    Context::set( 'key', 1 );
    Handler::save( 'A', Context::getContext() );

    Context::newContext();
    Context::set( 'key', 2 );

    throw new Exception( '{[A]key}{key}{key[A]}' );
} catch ( Exception $e ) {
    $message = $e->getMessage(); // A mensagem será: 121
    echo $message;
}

```