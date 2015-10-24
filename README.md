# JASMINE

JASMINE is **J**ust **A**nother **S**NMP **M**anager for **I**maginary **NE**tworks.

Trabalho de Gerência e Administração de Redes UNISC 2015/2

## Implementação

Feito em PHP com o framework CakePHP. Interface feita utilizando Bootstrap.

## Instalação

Instalar as dependências pelo Composer:

    $ composer install

Copiar o arquivo `config/app.default.php` para `config/app.php` e configurar a conexão com o banco de dados:

```php
// ...
'Datasources' => [
    'default' => [
        'className' => 'Cake\Database\Connection',
        'driver' => 'Cake\Database\Driver\Mysql',
        'persistent' => false,
        'host' => 'localhost',
        'port' => '3306',
        'username' => 'user',
        'password' => 'password',
        'database' => 'jasmine',
        // ...
    ]
]
```

Criar as tabelas no banco de dados, utilizando o `cake migrations`:

    $ cake migrations migrate
