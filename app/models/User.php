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

}