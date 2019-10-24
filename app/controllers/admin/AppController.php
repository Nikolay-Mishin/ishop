<?php

namespace app\controllers\admin;

use app\models\AppModel;
use app\models\User;
use ishop\base\Controller;

// Базовый контреллер админки (панели администратора)

class AppController extends Controller {

    public $layout = 'admin'; // шаблон админки

    public function __construct($route){
        parent::__construct($route); // наследуем родительский конструктор
        // если не админ и не страница авторизации, перенаправляем на страницу авторизации админа
        if(!User::isAdmin() && $route['action'] != 'login-admin'){
            redirect(ADMIN . '/user/login-admin'); // UserController::loginAdminAction
        }
        new AppModel(); // создаем объект базовой модели для доступа к sql
    }

}