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
        // записываем в контейнер (реестр) список доступных валют и текущую валюту
        App::$app->setProperty('currencies', Currency::getCurrencies());
        App::$app->setProperty('currency', Currency::getCurrency(App::$app->getProperty('currencies')));
        // debug(App::$app->getProperties()); // распечатываем список параметров из реестра
        App::$app->setProperty('cats', self::cacheCategory()); // записываем категории в контейнер
    }

    // метод для кэширования массива категорий
    public static function cacheCategory(){
        $cache = Cache::instance(); // объет кэша
        $cats = $cache->get('cats'); // получаем категории из кэша
        // если данные из кэша не получены, то получаем их из БД и записываем в кэш
        if(!$cats){
            $cats = \R::getAssoc("SELECT * FROM category");
            $cache->set('cats', $cats);
        }
        return $cats;
    }

}