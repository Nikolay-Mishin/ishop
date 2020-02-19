<?php
// конфигурации БД - ORM RedBeanPHP

return [
    'dsn' => "mysql:host={$_SERVER['HTTP_HOST']};dbname=".DB_NAME.";charset=utf8",
    'user' => 'root',
    'pass' => '',
];
