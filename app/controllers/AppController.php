<?php
// базовый класс приложения, который наследуют остальные контроллеры

namespace app\controllers;

use app\models\AppModel;
use ishop\base\Controller; // подключаем базовый класс Контроллера

class AppController extends Controller{

    public function __construct($route){
        parent::__construct($route);
        new AppModel();
    }

}