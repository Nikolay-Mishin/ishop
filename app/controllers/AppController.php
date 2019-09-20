<?php

namespace app\controllers;

use app\models\AppModel; // подключаем базовый класс Моделей приложения
use ishop\base\Controller; // подключаем базовый класс Контроллера фреймворка
use app\widgets\currency\Currency;
use ishop\App;

class AppController extends Controller{

    public function __construct($route){
        // перегрузка - переопределение методов и свойств родительского класса
        parent::__construct($route); // вызов родительского конструктора, чтобы его не затереть
        new AppModel(); // создаем объект базовой модели приложения
        setcookie('currency', 'EUR', time() + 3600*24*7, '/');
        App::$app->setProperty('currencies', Currency::getCurrencies());
        App::$app->setProperty('currency', Currency::getCurrency(App::$app->getProperty('currencies')));
        debug(App::$app->getProperties());
    }

}