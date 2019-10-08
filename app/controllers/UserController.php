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
            if(!$user->validate($data)){
                $user->getErrors();
                redirect();
            }else{
                $_SESSION['success'] = 'OK';
                redirect();
            }
        }
        $breadcrumbs = Breadcrumbs::getBreadcrumbs(null, 'Регистрация'); // хлебные крошки
        $this->setMeta('Регистрация');
        $this->set(compact('breadcrumbs'));
    }

    // авторизация пользователя
    public function loginAction(){

    }

    // выход для авторизованного пользователя
    public function logoutAction(){

    }

}