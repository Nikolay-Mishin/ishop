<?php
// базовый класс модели фреймворка - описывает базовые свойства и методы, которые будут наследоваться всеми моделями
// необходим для работы с БД

namespace ishop\base;

use ishop\Db; // класс БД
use Valitron\Validator; // класс валидации

abstract class Model{

    public $attributes = []; // массив свойств модели (идентичен полям в таблицах БД - автозагрузка данных из форм в модель)
    public $errors = []; // хранение ошибок
    public $rules = []; // правила валидации данных

    public function __construct(){
        Db::instance(); // создаем объект класса БД
    }

    // метод автозагрузки данных из формы
    // защищает от получения данных, которых нет в форме (будут заполнены только данные, которые есть в аттрибутах модели)
    /*
    'login' => '',
    'password' => '',
    // данного поля нет в свойстве $attributes
    'login1' => '', // поле, которого не было в форме (если со стороны клиенты, например будет попытка подмены формы)
    */
    public function load($data){
        foreach($this->attributes as $name => $value){
            if(isset($data[$name])){
                $this->attributes[$name] = $data[$name];
            }
        }
    }

    public function validate($data){
        Validator::langDir(WWW . '/validator/lang');
        Validator::lang('ru');
        $v = new Validator($data);
        $v->rules($this->rules);
        if($v->validate()){
            return true;
        }
        $this->errors = $v->errors();
        return false;
    }

    public function getErrors(){
        $errors = '<ul>';
        foreach($this->errors as $error){
            foreach($error as $item){
                $errors .= "<li>$item</li>";
            }
        }
        $errors .= '</ul>';
        $_SESSION['error'] = $errors;
    }

}