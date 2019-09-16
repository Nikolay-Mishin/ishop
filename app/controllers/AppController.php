<?php
// базовый класс Контроллеров приложения, который наследуют остальные контроллеры
// extends (наследует базовый класс контроллера) - AppController наследует класс Controller (расширяет/дополняет его)

namespace app\controllers;

use app\models\AppModel; // подключаем базовый класс Моделей приложения
use ishop\base\Controller; // подключаем базовый класс Контроллера

class AppController extends Controller{

    public function __construct($route){
        parent::__construct($route);
        new AppModel();
    }

}