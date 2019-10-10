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

    // авторизация пользователя
    // за авторизацию отвечает 1 метод - и для обычного пользователя, и для админа
    // админ может авторизоваться как со страницы админки, так и со страницы авторизации обычного пользователя
    // пользователь может авторизоваться только на странице авторизации обычного пользователя и получает доступ к личному кабинету
    public function login($isAdmin = false){
        // $isAdmin - является ли пользователь админом
        $login = !empty(trim($_POST['login'])) ? trim($_POST['login']) : null; // получаем логин из формы авторизации
        $password = !empty(trim($_POST['password'])) ? trim($_POST['password']) : null; // получаем пароль из формы авторизации
        if($login && $password){
            // проверяем роль пользователя и получаем из БД пользователя с соответствующей ролью (admin/user)
            if($isAdmin){
                $user = \R::findOne('user', "login = ? AND role = 'admin'", [$login]);
            }else{
                $user = \R::findOne('user', "login = ?", [$login]);
            }
            // если пользователь получен, то проверяем правильность введенного пароля
            if($user){
                // password_verify - сравнивает хэш пароля (1 - из формы, 2 - из БД)
                if(password_verify($password, $user->password)){
                    $this->saveSession($user); // записываем в сессиию все данные пользователя, кроме пароля
                    return true;
                }
            }
        }
        return false;
    }

    // сохраняет данные авторизованного пользователя в сессию
    public function saveSession($user = null){
        $user = $user ?? $this->attributes;
        foreach($user as $k => $v){
            if($k != 'password') $_SESSION['user'][$k] = $v;
        }
    }

    // проверяет авторизован ли пользователь
    public static function isLogin(){
        if(isset($_SESSION['user'])) return true; // если в сессии есть данные пользователя (авторизован), возвращаем true
        return false;
    }

}