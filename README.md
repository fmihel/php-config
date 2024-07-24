# php-config 
``v1.0.0``

## Загрузчик конфигурационного файла для приложений на php.

Конфигурационный файл - файл содержащий настройки для конкретного места развертывания приложения.
К примеру данные для подключения к базе. ``Данный файл ни в коем случае нельзя сохранять в git или подобное.``

---
## Установка
```composer require fmihel/php-config```

---
## Использование
Простейшая структура
```
app
 |--config.php
 |--index.php

```
___config.php___
```php
<$php
$config=[
    'pics'=>'app/pics/',
    'base'=>'db',
    'pass'=>'xxxx',
    'user'=>'mike',
    'emails'=>[
        'admin'=>'xxx@xxx.xx',
    ]
];
```
___index.php___
```php
require_once __DIR__.'/vendor/autoload.php';
use fmihel\config\Config;

echo Config::get('pics');// app/pics
echo Config::get('no-def','default');// default
echo Config::get('no-def');  // raise Exception  
echo Config::get('emails',['admin'=>'bbb@bbb.bb']);  // ['admin'=>'xxx@xxx.xx']
echo Config::get('emails',['info'=>'aaa@aaa.aa']);  // ['admin'=>'xxx@xxx.xx','info'=>'aaa@aaa.aa']
```
---
## Использование шаблона конфигурации:
Использование шаблона конфигурации позволяет поддерживать структуру конфигурационного файла в актуальности. Если структура шаблона будет отличаться от конфигурации к config приложение будет остановлено и выданы соотвествующие предупреждения. Шаблон конфигурации можно и нужно хранить в репозиториях и деплоить с приложением.
```
app
 |--config.php
 |--config.template.php
 |--index.php

```
___config.php___
```php
<$php
$config=[
    'pics'=>'app/pics/',
    'base'=>'db',
    'pass'=>'xxxx',
    'user'=>'mike',
];
```
___config.template.php___
```php
<$php
$configTemplate=[
    'pics'=>'string',
    'base'=>'string',
    'pass'=>'string',
    'user'=>'string',
    'age'=>'number',
    'local'=>'bool'
];
```
___index.php___
```php
require_once __DIR__.'/vendor/autoload.php';
use fmihel\config\Config; // после этой строчке
// приложение будет остановлено !!!
// так как структура config отличается от config.template

```
---
English version
## Config file loader for php application;
### Install
```composer require fmihel/php-config```
