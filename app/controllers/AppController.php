<?php
// базовый класс Контроллеров приложения, который наследуют остальные контроллеры
// extends (наследует базовый класс контроллера фреймворка) - AppController наследует класс Controller (расширяет/дополняет его)

namespace app\controllers;

use app\models\AppModel; // подключаем базовый класс Моделей приложения
use ishop\base\Controller; // подключаем базовый класс Контроллера фреймворка

class AppController extends Controller{

    public function __construct($route){
        // перегрузка - переопределение методов и свойств родительского класса
        parent::__construct($route); // вызов родительского конструктора, чтобы его не затереть
        new AppModel(); // создаем объект базовой модели приложения
    }

}