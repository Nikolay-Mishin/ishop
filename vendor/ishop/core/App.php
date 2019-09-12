<?php
// класс ядра приложения
// реализует паттерн (шаблон проектирования) Реестр (Registry)

namespace ishop;

class App{

    public static $app; // контейнер для приложения (хранение свойств/объектов)

    public function __construct(){
        // отсекаем концевой / строки запроса (после доменного имени http://ishop2.loc/)
        $query = trim($_SERVER['QUERY_STRING'], '/');
        session_start(); // стартуем сессию
        self::$app = Registry::instance(); // запишем в свойство приложения объект Реестра
        $this->getParams(); // получаем параметры приложения
        new ErrorHandler();
    }

    protected function getParams(){
        $params = require_once CONF . '/params.php'; // массив параметров (настроек) приложения
        // записываем массив параметров в реестр
        if(!empty($params)){
            foreach($params as $k => $v){
                self::$app->setProperty($k, $v);
            }
        }
    }

}