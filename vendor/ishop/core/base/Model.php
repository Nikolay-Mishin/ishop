<?php
// базовый класс модели фреймворка - описывает базовые свойства и методы, которые будут наследоваться всеми моделями
// необходим для работы с БД

namespace ishop\base;

use ishop\Db; // класс БД
use Valitron\Validator; // класс Валидатора

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
            // если в данных есть поле, соответствующее полю в $attributes, то записываем значение из данных в аттрибуты
            if(isset($data[$name])){
                $this->attributes[$name] = $data[$name];
            }
        }
    }

    // метод валидации данных
    public function validate($data){
        Validator::langDir(WWW . '/validator/lang'); // указываем Валидатору директорию для языков
        Validator::lang('ru'); // устанавливаем язык Валидатора
        $v = new Validator($data); // объект Валидатора (передаем данные в конструктор)
        $v->rules($this->rules); // передаем в Валидатор набор правил валидации
        // если валидация прошла успешно, возвращаем true
        if($v->validate()){
            return true;
        }
        $this->errors = $v->errors(); // записываем ошибки валидации в свойство модели
        return false;
    }

    // получает ошибки валидации
    public function getErrors(){
        // формируем список ошибок
        $errors = '<ul>';
        foreach($this->errors as $error){
            foreach($error as $item){
                $errors .= "<li>$item</li>";
            }
        }
        $errors .= '</ul>';
        $_SESSION['error'] = $errors; // записываем список ошибок в сессию
    }

}