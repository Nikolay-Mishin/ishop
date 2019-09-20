<?php
// базовый класс Контроллеров приложения, который наследуют остальные контроллеры

namespace app\controllers;

use ishop\base\Controller; // подключаем базовый класс Контроллера фреймворка
use app\models\AppModel; // подключаем базовый класс Моделей приложения
use ishop\App;
use app\widgets\currency\Currency;

class AppController extends Controller{

    public function __construct($route){
        // перегрузка - переопределение методов и свойств родительского класса
        parent::__construct($route); // вызов родительского конструктора, чтобы его не затереть
        new AppModel(); // создаем объект базовой модели приложения
        setcookie('currency', 'EUR', time() + 3600*24*7, '/'); // записываем валюту в куки на 1 неделю для всего домена ('/')
        // записываем список доступных валют и текущую валютув реестр
        App::$app->setProperty('currencies', Currency::getCurrencies());
        App::$app->setProperty('currency', Currency::getCurrency(App::$app->getProperty('currencies')));
        debug(App::$app->getProperties()); // распечатываем список параметров из реестра
    }

}