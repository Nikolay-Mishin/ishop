<?php
// класс БД - подключение к БД
// реализует паттерн Singletone

namespace ishop;

class Db{

    use TSingletone;

    protected function __construct(){
        $db = require_once CONF . '/config_db.php'; // подключаем файл конфигурации БД
    }

}