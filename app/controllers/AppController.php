<?php
// базовый класс Контроллеров приложения, который наследуют остальные контроллеры

namespace app\controllers;

use ishop\base\Controller; // подключаем базовый класс Контроллера фреймворка
use app\models\AppModel; // подключаем базовый класс Моделей приложения
use ishop\App; // подключаем класс базовый приложения
use app\widgets\currency\Currency; // подключаем виджет валюты
use ishop\Cache; // подключаем класс кэша

class AppController extends Controller{

    public function __construct($route){
        // перегрузка - переопределение методов и свойств родительского класса
        parent::__construct($route); // вызов родительского конструктора, чтобы его не затереть
        new AppModel(); // создаем объект базовой модели приложения
        // записываем список доступных валют и текущую валютув реестр
        App::$app->setProperty('currencies', Currency::getCurrencies());
        App::$app->setProperty('currency', Currency::getCurrency(App::$app->getProperty('currencies')));
        // debug(App::$app->getProperties()); // распечатываем список параметров из реестра
        App::$app->setProperty('cats', self::cacheCategory());
    }

    public static function cacheCategory(){
        $cache = Cache::instance();
        $cats = $cache->get('cats');
        if(!$cats){
            $cats = \R::getAssoc("SELECT * FROM category");
            $cache->set('cats', $cats);
        }
        return $cats;
    }

}