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

}