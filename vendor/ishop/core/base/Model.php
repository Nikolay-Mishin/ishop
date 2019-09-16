<?php
// базовый класс модели - описывает базовые свойства и методы, которые будут наследоваться всеми моделями приложения
// необходим для работы с БД

namespace ishop\base;

use ishop\Db;

abstract class Model{

    public $attributes = []; // массив свойств модели (идентичен полям в таблицах БД - автозагрузка данных из форм в модель)
    public $errors = []; // хранение ошибок
    public $rules = []; // правило валидации данных

    public function __construct(){
        Db::instance();
    }

}