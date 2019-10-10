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
            $user->load($data); // загружаем (массово) данные в модель (из $data в $user->attributes)
            // если валидация не пройдена, получаем список ошибок и перенаправляем пользователя на текущую страницу
            // проверяем уникальные поля с данными
            if(!$user->validate($data) || !$user->checkUnique()){
                $user->getErrors(); // получаем список ошибок
                $_SESSION['form_data'] = $data; // записываем в сессию данные, чтобы значения заполненных полей не сбрасывались
            }else{
                // хэшируем пароль
                // password_hash - хэширует пароль с учетом временной метки (текущей даты)
                $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
                // сохраняем нового пользователя в БД
                // $user->save('user') - благодаря методу getModelName() имя Модели используется в качестве имени таблицы в БД
                // если имя таблицы не передано, ModelName = TableName в БД (User => user)
                if($user->save()){
                    $_SESSION['success'] = 'Пользователь зарегистрирован'; // записываем в сессию сообщение об успешной регистрации
                    $user->saveSession(); // записываем в сессиию все данные пользователя, кроме пароля
                }else{
                    $_SESSION['error'] = 'Ошибка!'; // записываем в сессию сообщение об успешной регистрации
                }
            }
            $_SESSION['user.errors'] = $user->errors; // ошибки при регистрации (выводить под полями формы)
            redirect(); // перезапрашиваем страницу
        }
        $breadcrumbs = Breadcrumbs::getBreadcrumbs(null, 'Регистрация'); // хлебные крошки
        $errors = $_SESSION['user.errors'] ?? []; // записываем ошибки регистрации в переменную и передаем ее в вид
        $this->setMeta('Регистрация'); // устанавливаем мета-данные
        $this->set(compact('breadcrumbs', 'errors')); // передаем данные в вид
    }

    // авторизация пользователя
    public function loginAction(){
        if(User::isLogin()) redirect(PATH); // если пользователь уже авторизован перенапрвляем на главную
        $_SESSION['redirect'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : PATH;
        // если получены данные методом POST, обрабатываем их и регистрируем пользователя
        if(!empty($_POST)){
            $user = new User(); // объект модели пользователя
            // авторизовываем пользователя и выводим сообщение об успешной/не успешной авторизации
            if($user->login()){
                $_SESSION['success'] = 'Вы успешно авторизованы';
            }else{
                $_SESSION['error'] = 'Логин/пароль введены неверно';
            }
            $_SESSION['user.errors'] = $user->errors; // ошибки при авторизации (выводить под полями формы)
            redirect($_SESSION['redirect']); // перезапрашиваем страницу
        }
        $breadcrumbs = Breadcrumbs::getBreadcrumbs(null, 'Регистрация'); // хлебные крошки
        $errors = $_SESSION['user.errors'] ?? []; // записываем ошибки авторизации в переменную и передаем ее в вид
        $this->setMeta('Вход'); // устанавливаем мета-данные
        $this->set(compact('breadcrumbs', 'errors')); // передаем данные в вид
    }

    // выход для авторизованного пользователя
    public function logoutAction(){
        if(isset($_SESSION['user'])) unset($_SESSION['user']); // если в сессии есть данные пользователя (авторизован), удаляем их
        redirect(); // перезапрашиваем страницу
    }

}