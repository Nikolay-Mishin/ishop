<?php
// Контреллер пользователя - регистрация, авторизация и выход

namespace app\controllers;

use app\models\User; // модель пользователя
use app\models\Breadcrumbs; // модель хлебных крошек

class UserController extends AppController {

    // регистрция пользователя
    public function signupAction(){
        // если получены данные методом POST, обрабатываем их и регистрируем пользователя
        if(!empty($_POST)){
            $user = new User(); // объект модели пользователя
            $data = $_POST; // данные, пришедшие от пользователя (записываем в переменную, чтобы не работать напрямую с массивом POST)
            $user->load($data); // загружаем данные в модель (из $data в $user->attributes)
            // если валидация не пройдена, получаем список ошибок и перенаправляем пользователя на текущую страницу
            // проверяем уникальные поля с данными
            if(!$user->validate($data) || !$user->checkUnique()){
                $user->getErrors(); // получаем список ошибок
            }else{
                // хэшируем пароль
                // password_hash - хэширует пароль с учетом временной метки (текущей даты)
                $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
                // сохраняем нового пользователя в БД
                if($user->save('user')){
                    $_SESSION['success'] = 'Пользователь зарегистрирован'; // записываем в сессию сообщение об успешной регистрации
                }else{
                    $_SESSION['error'] = 'Ошибка!'; // записываем в сессию сообщение об успешной регистрации
                }
            }
            $_SESSION['user.errors'] = $user->errors;
            redirect(); // перезапрашиваем страницу
        }
        $breadcrumbs = Breadcrumbs::getBreadcrumbs(null, 'Регистрация'); // хлебные крошки
        $errors = $_SESSION['user.errors'] ?? [];
        $this->setMeta('Регистрация');
        $this->set(compact('breadcrumbs', 'errors'));
    }

    // авторизация пользователя
    public function loginAction(){

    }

    // выход для авторизованного пользователя
    public function logoutAction(){

    }

}