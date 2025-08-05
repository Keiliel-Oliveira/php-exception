# Controle de instancias de contexto

Algo que pode ser necessário é criar uma nova instancia de contexto para preparar uma nova possível exceção, porém, também pode ocorrer de que o contexto atual talvez seja útil em outro ponto, para isso temos a classe *Handler* para controle de instancias de contexto.

Essa classe fornece métodos básicos para controle de instancias de contexto como listados abaixo.

Uma observação importante é que essa classe possui duas versões, uma é a principal e a outra é uma interface estática da primeira.

# Métodos

## save()

Esse método é acessível tanto pela interface estática como pela classe principal, ele salva uma instancia do contexto.

### parâmetros:

- ***string $name***: Um nome que servirá como identificado único dessa instancias.
- ***KeilielOliveira\PhpException\Context $contex***: A instancia da classe de contexto a ser salva.

### retorno:

Nenhum.

### exceções:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;
use KeilielOliveira\PhpException\Facades\Handler;

require_once 'vendor/autoload.php';

Handler::save('A', Context::getContext());

// Uma exceção será lançada pois o nome 'A' já foi usado.
Handler::save('A', Context::getContext());
```

### observações:

Nenhuma.

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;
use KeilielOliveira\PhpException\Facades\Handler;

require_once 'vendor/autoload.php';

Context::set('key', 'value');
Handler::save('A', Context::getContext());
```

## update()

Esse método é acessível tanto pela interface estática como pela classe principal, ele atualiza uma instancia de contexto.

### parâmetros:

- ***string $name***: Um nome que é o identificado único dessa instancia.
- ***KeilielOliveira\PhpException\Context $contex***: A instancia da classe de contexto a ser salva.

### retorno:

Nenhum.

### exceções:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;
use KeilielOliveira\PhpException\Facades\Handler;

require_once 'vendor/autoload.php';

// Uma exceção será lançada pois o nome 'A' não existe.
Handler::update('A', Context::getContext());
```

### observações:

Nenhuma.

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;
use KeilielOliveira\PhpException\Facades\Handler;

require_once 'vendor/autoload.php';

Context::set('key_one', 'value');
Handler::save('A', Context::getContext());

Context::set('key_two', 'value');
Handler::update('A', Context::getContext());
```

## delete()

Esse método é acessível tanto pela interface estática como pela classe principal, ele deleta uma instancia salva.

### parâmetros:

- ***string $name***: Um nome que é o identificado único dessa instancia.

### retorno:

Nenhum.

### exceções:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;
use KeilielOliveira\PhpException\Facades\Handler;

require_once 'vendor/autoload.php';

// Uma exceção será lançada pois o nome 'A' não existe.
Handler::delete('A');
```

### observações:

Nenhuma.

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;
use KeilielOliveira\PhpException\Facades\Handler;

require_once 'vendor/autoload.php';

Context::set('key_one', 'value');
Handler::save('A', Context::getContext());

// A instancia 'A' agora não pode mais ser acessada.
Handler::delete('A');
```

## get()

Esse método é acessível tanto pela interface estática como pela classe principal, ele retorna uma instancia salva.

### parâmetros:

- ***string $name***: Um nome que é o identificado único da instancia a ser buscada.

### retorno:

Retorna ```KeilielOliveira\PhpException\Context```.

### exceções:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;
use KeilielOliveira\PhpException\Facades\Handler;

require_once 'vendor/autoload.php';

// Uma exceção será lançada pois o nome 'A' não existe.
Handler::get('A');
```

### observações:

Nenhuma.

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;
use KeilielOliveira\PhpException\Facades\Handler;

require_once 'vendor/autoload.php';

Context::set('key_one', 'value');
Handler::save('A', Context::getContext());

// $context agora possui a instancia do contexto.
$context = Handler::get('A');
```

## has()

Esse método é acessível tanto pela interface estática como pela classe principal, ele verifica se determinada instancia existe.

### parâmetros:

- ***string $name***: Um nome que é o identificado único da instancia a ser buscada.

### retorno:

Retorna *true* se a instancia existir e *false* se não existir.

### exceções:

Nenhuma.

### observações:

Nenhuma.

### exemplos de uso:

```php
<?php

use KeilielOliveira\PhpException\Facades\Context;
use KeilielOliveira\PhpException\Facades\Handler;

require_once 'vendor/autoload.php';

Context::set('key_one', 'value');
Handler::save('A', Context::getContext());

Handler::has('A'); // Vai retornar true.
Handler::has('B'); // Vai retornar false.
```