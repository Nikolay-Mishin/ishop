<?php

namespace app\controllers\admin;

use \Exception;

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
        if(CUSTOM_DB_INSTANCE) new AppModel(); // создаем объект базовой модели для доступа к sql
    }

    // получает параметр id из массива get или post
    // по умолчанию берет значение из массива get
    // по умолчанию значение берется из параметра id
    public function getRequestID($id = 'id'){
        // если в массиве есть параметр id, приводим его к числу, иначе записываем null
        $id = !empty($_GET[$id]) ? (int)$_GET[$id] : (!empty($_POST[$id]) ? (int)$_POST[$id] : null);
        // выбрасываем исключение, если не получен id
        if(!$id){
            throw new Exception('Страница не найдена', 404);
        }
        return $id;
    }

}
