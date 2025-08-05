# Controle de contexto

A biblioteca possui a classe ```KeilielOliveira\PhpException\Facades\Context``` que é usada para controlar o contexto das exceções, essa classe possui diversos métodos que visam armazenar dados para serem usados posteriormente na geração de exceções mais descritivas.

Uma observação importante é que essa classe possui duas versões, uma é a principal e a outra é uma interface estática da primeira.

# Métodos

Abaixo segue-se uma lista dos métodos dessa classe, suas definições e exemplos de uso.

## newContext()

Esse método é exclusivo da interface estática, ele permite a criação de uma nova instancia da classe principal ```KeilielOliveira\PhpException\Context```.

### parâmetros:

Nenhum.

### retorno:

nenhum.

### exceções:

Nenhuma.

### observações:

Nenhuma.

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

// Deleta a instancia do contexto anterior e prepara para a criação de uma nova.
Context::newContext();
```

## getContext()

Esse método é exclusivo da interface estática, ele retorna a instancia do contexto atual.

### parâmetros:

Nenhum.

### retorno:

Retorna ```KeilielOliveira\PhpException\Context```.

### exceções:

Nenhuma.

### observações:

Caso nenhuma instancia tenha sido definida ainda, ele cria uma nova e retorna, porém ele não salva essa instancia para uso interno, ele meramente retorna uma nova instancia.

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

// Retorna uma nova instancia do contexto.
Context::getContext();
```

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

Context::set('key', 'value');

// Retorna a instancia sendo usada atualmente.
Context::getContext();
```

## set()

Esse método é acessível tanto pela interface estática como pela classe principal, ele salva o valor recebido dentro da chave recebida no contexto atual.

### parâmetros:

- ***string $key***: Chave única onde a instancia será salva.
- ***mixed $value***: Valor a ser salvo.

### retorno:

Nenhum.

### exceções:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

Context::set('key', 'value');

// Uma exceçãos será lançada caso uma chave já usada anteriormente seja novamente usada para a definição.
Context::set('key', 'value');
```

### observações:

Nenhuma.

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

Context::set('key', 'value');
```

## forceSet()

Esse método é acessível tanto pela interface estática como pela classe principal, ele salva o valor recebido dentro da chave recebida no contexto atual, independentemente se a chave foi usada anteriormente ou não.

### parâmetros:

- ***string $key***: Chave única onde a instancia será salva.
- ***mixed $value***: Valor a ser salvo.

### retorno:

Nenhum.

### exceções:

Nenhuma.

### observações:

Este método é util para por exemplo valores que estão mudando constantemente, ele força a definição do valor, como um auto atualização, portanto deve-se ter os devidos cuidados com seu uso.

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

$array = [0, 1, 2, 3, 4, 5];

foreach ($array as $key => $value) {

    // Ao usar o set(), uma exceção será lançada na segunda vez que arg estiver sendo definida.
    Context::set('arg', $value);

    // Ao usar o forceSet(), o valor de arg sempre será o atual do loop.
    Context::forceSet('arg', $value);

}
```

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

Context::set('key', 'value');

// Nenhuma exceção será lançada, já que essa validação é ignorada e o valor é substituído.
Context::forceSet('key', 'new value');
```

## update()

Esse método é acessível tanto pela interface estática como pela classe principal, ele atualiza o valor da chave recebida pelo valor recebido.

### parâmetros:

- ***string $key***: Chave única do contexto.
- ***mixed $value***: Valor a ser salvo.

### retorno:

Nenhum.

### exceções:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

// Uma exceção será lançada pois a chave 'key' não existe.
Context::update('key', 'new value');
```

### observações:

Nenhuma.

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

Context::set('key', 'value');

// A chave 'key' agora terá 'new value' como valor.
Context::update('key', 'new value');
```

## delete()

Esse método é acessível tanto pela interface estática como pela classe principal, ele deleta uma ou mais chaves do contexto atual.

### parâmetros:

- ***string $key***: Chave única do contexto.

### retorno:

Nenhum.

### exceções:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

// Uma exceção será lançada pois a chave 'key' não existe.
Context::delete('key');
```

### observações:

O método aceita uma ou mais chaves para serem deletadas.

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

Context::set('key_one', 'one');
Context::set('key_two', 'two');

// Ambas as chaves serão deletadas.
Context::delete('key_one', 'key_two');
```

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

Context::set('key', 'value');

// A chave 'key' será deletada juntamente com seu respectivo valor.
Context::delete('key');
```

## clear()

Esse método é acessível tanto pela interface estática como pela classe principal, ele deleta todos os dados ou somente os não retornados por uma função de callback.

### parâmetros:

- ***null|callable $callback***: Função de callback que irá receber todos os dados do contexto, os dados retornados por ela não serão deletados.

### retorno:

Nenhum.

### exceções:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

Context::set('key', 'value');

// Uma exceção será lançada pois a função de callback não atende a todos os requisitos.
Context::clear(function() {});
```

### observações:

A função de callback deve receber um único parâmetro do tipo *array* e deve também retornar *array*, caso isso não seja especificado, uma exceção sera lançada.

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

Context::set('key', 'value');

Context::clear(function(array $args): array {
    return $args;
});
```

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

Context::set('key_one', 'value');
Context::set('key_two', false);

Context::clear(function(array $context): array {
    return array_filter($context, function(mixed $v): bool {
        return !is_bool($v);
    });
});
```

## has()

Esse método é acessível tanto pela interface estática como pela classe principal, ele verifica se a chave recebida existe.

### parâmetros:

- ***string $key***: Chave cuja a existência sera verificada.

### retorno:

Retorna *true* se a chave existir e *false* se não existir.

### exceções:

Nenhuma

### observações:

Nenhuma

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

Context::set('key', 'value');

Context::has('key'); // Irá retornar true.
Context::has('key_one'); // Irá retornar false.
```

## get()

Esse método é acessível tanto pela interface estática como pela classe principal, ele procura e retorna o valor de uma chave.

### parâmetros:

- ***string $key***: Chave que será procurada.
- ***callable|null***: Função de callback que irá ser executada caso a chave exista.

### retorno:

Retorna *mixed*.

### exceções:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

// Irá lançar uma exceção pois a chave 'key' não existe.
Context::get('key');
```

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

Context::set('key', 'value');

// Irá lançar uma exceção pois a função de callback não possui todos os requisitos.
Context::get('key', function() {});
```

### observações:

A função de callback deve receber um único parâmetro do tipo *mixed* e deve retornar *mixed*, caso contrario uma exceção será lançada.

Caso a função de callback seja passada, o valor recuperado da chave será passado a ela e o retorno do método *get()* será o retorno da função de callback.

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

Context::set('key', 'value');

// Irá retornar 'value'.
Context::get('key');
```

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

Context::set('key', 'value');

// Irá retornar 'VALUE'.
Context::get('key', function(mixed $arg): mixed {
    return is_string($arg) ? strtoupper($arg) : $arg;
});
```

## getAll()

Esse método é acessível tanto pela interface estática como pela classe principal, ele retorna todos os valores registrados no contexto.

### parâmetros:

- ***callable|null***: Função de callback que irá ser executada com os dados do contexto.

### retorno:

Retorna *array|null*.

### exceções:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

Context::set('key', 'value');

// Irá lançar uma exceção pois a função de callback não possui todos os requisitos.
Context::getAll(function() {});
```

### observações:

A função de callback deve receber um único parâmetro do tipo *array* e deve retornar *array*, caso contrario uma exceção será lançada.

Caso a função de callback seja passada, todos os dados do contexto serão passados a ela e seu retorno será o valor retornado pelo método *getAll()*.

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

Context::set('key', 'value');

// Irá retornar ['key' => 'value'].
Context::getAll('key');
```

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

Context::set('key', 'value');

// Irá retornar ['key' => 'VALUE'].
Context::getAll( function(array $args): array {
    return array_map(function(mixed $v): mixed {
        return is_string($v) ? strtoupper($v) : $v;
    }, $args);
});
```

## ifHas()

Esse método é acessível tanto pela interface estática como pela classe principal, ele defini que se determinada chave existir, o próximo método será executado, caso contrario, não será executado.

### parâmetros:

- ***string***: A chave única cuja existencia será verificada.

### retorno:

Retorna ```KeilielOliveira\PhpException\Facades\Context``` se o método for chamado pela interface e ```KeilielOliveira\PhpException\Context``` se o método for chamado pela classe principal.

### exceções:

Nenhuma.

### observações:

Essa funcionalidade só está disponivel aos métodos *set()*, *forceSet()*, *update()*, *delete()*, *clear()*, *get()* e *getAll()*.

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

// Se a chave 'key' existir, seu valor será atualizado para 'new value'.
Context::ifHas('key')::update('key', 'new value');

// Também pode ser escrito dessa forma.
Context::ifHas('key');
Context::update('key', 'new value');
```

## ifNotHas()

Esse método é acessível tanto pela interface estática como pela classe principal, ele defini que se determinada chave não existir, o próximo método será executado, caso contrario, não será executado.

### parâmetros:

- ***string***: A chave única cuja existencia será verificada.

### retorno:

Retorna ```KeilielOliveira\PhpException\Facades\Context``` se o método for chamado pela interface e ```KeilielOliveira\PhpException\Context``` se o método for chamado pela classe principal.

### exceções:

Nenhuma.

### observações:

Essa funcionalidade só está disponivel aos métodos *set()*, *forceSet()*, *update()*, *delete()*, *clear()*, *get()* e *getAll()*.

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

// Se a chave 'key' não existir, ela será definida como 'value'.
Context::ifNotHas('key')::set('key', 'value');

// Também pode ser escrito dessa forma.
Context::ifHas('key');
Context::update('key', 'value');
```

## when()

Esse método é acessível tanto pela interface estática como pela classe principal, ele defini que se a função de callback passada retornar true, o próximo método será executado.

### parâmetros:

- ***callable $callback***: A função de callback a ser executada.

### retorno:

Retorna ```KeilielOliveira\PhpException\Facades\Context``` se o método for chamado pela interface e ```KeilielOliveira\PhpException\Context``` se o método for chamado pela classe principal.

### exceções:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

// Uma exceção será lançada pois a função não possui todos os requisitos.
Context::when(function() {});
```

### observações:

Essa funcionalidade só está disponivel aos métodos *set()*, *forceSet()*, *update()*, *delete()*, *clear()*, *get()* e *getAll()*.

A função de callback deve receber um único parâmetro do tipo ```KeilielOliveira\PhpException\Context``` e deve retornar *bool*.

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

// Se a chave 'key' existir, seu valor será atualizado para 'new value'.
Context::when(function(\KeilielOliveira\PhpException\Context $context): bool {
    return $context->has('key');
});

Context::update('key', 'new value');
```

## separate()

Esse método é acessível tanto pela interface estática como pela classe principal, ele separa os valores das chaves recebidas e os retorna no formato de array.

### parâmetros:

- ***string $keys***: As chaves únicas a serem buscadas.

### retorno:

Retorna *array*.

### exceções:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

// Lança uma exceção caso uma das chaves não exista.
Context::separate('key');
```

### observações:

Esse método tem como objetivo isolar determinados valores em um array para contexto de uma exceção especifica.

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

Context::set('A', 1);
Context::set('B', 2);
Context::set('C', 3);

throw new Exception(sprintf('Ocorreram os erros %s e %s.', ...Context::separate('A', 'C')));
```

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

Context::set('A', 1);
Context::set('B', 2);
Context::set('C', 3);

// Será retornado [1, 3]
Context::separate('A', 'C');
```

## format()

Esse método é acessível tanto pela interface estática como pela classe principal, ele defini uma função de callback que irá ser executada para cada valor sendo recuperado no método *separate()*.

### parâmetros:

- ***callable $callback***: Função de callback que irá ser executada em cada valor do *separate()*.

### retorno:

Retorna ```KeilielOliveira\PhpException\Facades\Context``` se o método for chamado pela interface e ```KeilielOliveira\PhpException\Context``` se o método for chamado pela classe principal.

### exceções:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

// Uma exceção será lançada pois a função de callback não atende todos os requisitos.
Context::format(function() {});
```

### observações:

A função de callback espera receber um único parâmetro do tipo *mixed* e deve retornar *mixed*, caso contrario uma exceção sera lançada.

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;

require_once 'vendor/autoload.php';

// Todos os valores serão convertidos em string.
Context::format(function(mixed $arg): mixed {
    return is_string($arg) ? $arg : var_export($arg, true);
});
```