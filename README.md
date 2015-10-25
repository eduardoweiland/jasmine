# JASMINE

JASMINE is **J**ust **A**nother **S**NMP **M**anager for **I**maginary **NE**tworks.

Trabalho de Gerência e Administração de Redes UNISC 2015/2

## Implementação

JASMINE é desenvolvido na linguagem PHP utilizando o framework [CakePHP][]. A biblioteca utilizada para comunicação com os agentes é a [extensão SNMP padrão do PHP][PHP-SNMP], utilizando a API orientada a objetos disponível a partir da versão 5.4 do PHP.

A interface web é feita utilizando [Bootstrap][]. Os ícones utilizados são do [Font Awesome][].

## Interface

![Lista de dispositivos](design/Dispositivos_Lista.png)
![Cadastro de novo dispositivo](design/Dispositivos_Novo.png)
![Detalhes de um dispositivo](design/Dispositivos_Detalhes.png)
![Tela de monitoramento](design/Monitoramento.png)

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


[CakePHP]: http://cakephp.org "The rapid development PHP framework"
[PHP-SNMP]: http://php.net/manual/en/book.snmp.php "PHP SNMP Documentation"
[Bootstrap]: http://getbootstrap.com "Bootstrap Front-End Framework"
[Font Awesome]: http://fontawesome.io "Font Awesome The iconic font and CSS toolkit"
