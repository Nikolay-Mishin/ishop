<?php

namespace app\models\admin;

class User extends \app\models\User {

    // переопределяем аттрибуты родительской модели
    public $attributes = [
        'id' => '',
        'login' => '',
        'password' => '',
        'name' => '',
        'email' => '',
        'address' => '',
        'role' => '',
    ];

    // переопределяем правила валидации формы родительской модели
    public $rules = [
        'required' => [
            ['login'],
            ['name'],
            ['email'],
            ['role'],
        ],
        'email' => [
            ['email'],
        ],
    ];

    // переопределяем метод родительской модели для проверки уникальных полей с данными
    public function checkUnique(){
        // получаем пользователя с соответствующими значениями login или email из аттрибутов
        $user = \R::findOne('user', '(login = ? OR email = ?) AND id <> ?', [$this->attributes['login'], $this->attributes['email'], $this->attributes['id']]);
        // если пользователь найден, то формируем ошибки проверки уникальности
        if($user){
            if($user->login == $this->attributes['login']){
                $this->errors['unique'][] = 'Этот логин уже занят';
            }
            if($user->email == $this->attributes['email']){
                $this->errors['unique'][] = 'Этот email уже занят';
            }
            return false;
        }
        return true;
    }

}