<?php
// конфигурации БД - ORM RedBeanPHP

return [
    'lib' => 'scripts',
    'init' => "mysql:host={$_SERVER['HTTP_HOST']};dbname=".DB_NAME.";charset=utf8",
    'main' => 'admin'
];
