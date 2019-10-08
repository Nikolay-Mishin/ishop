<?php
// Модель пользователя

namespace app\models;

class User extends AppModel {

    // аттрибуты модели (параметры/поля формы)
    public $attributes = [
        'login' => '',
        'password' => '',
        'name' => '',
        'email' => '',
        'address' => '',
    ];

    // набор правил для валидации
    public $rules = [
        // обязательные поля
        'required' => [
            ['login'],
            ['password'],
            ['name'],
            ['email'],
            ['address'],
        ],
        // поле email
        'email' => [
            ['email'],
        ],
        // минимальная длина для поля
        'lengthMin' => [
            ['password', 6],
        ]
    ];

    // проверяет уникальные поля с данными
    public function checkUnique(){
        // получаем пользователя с соответствующими значениями login или email из аттрибутов
        $user = \R::findOne('user', 'login = ? OR email = ?', [$this->attributes['login'], $this->attributes['email']]);
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