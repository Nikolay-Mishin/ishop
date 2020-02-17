<?php
// класс ядра приложения
// реализует паттерн (шаблон проектирования) Реестр (Registry)

// namespace (пространство имен) - путь к данному классу относительно корня приложения
// PSR-4 - стандарт именования классов/скриптов и прочего (стандарт написания кода)
// абсолютное имя класса - \<ИмяПространстваИмён>(\<ИменаПодпространствИмён>)*\<ИмяКласса>
// ИмяПространстваИмён (vendor name) - имя производителя (разработчика/продукта) - виртуальная папка, которой в проекте нет
// (vendor name) ishop = vendor/ishop/core (псевдоним пути в автозагрузчике - composer.json)
// app = app

namespace ishop;

class App{

    public static $app; // контейнер (реестр) для приложения (хранение свойств/объектов)

    public function __construct(){
        // отсекаем концевой '/' строки запроса (после доменного имени http://ishop2.loc/)
        $query = trim($_SERVER['QUERY_STRING'], '/');
        session_start(); // стартуем сессию
        self::$app = Registry::instance(); // запишем в свойство приложения объект Реестра
        $this->getParams(); // получаем параметры приложения
        new ErrorHandler(); // создаем объект класса Исключений
        Router::dispatch($query); // передаем в маршрутизатор для обработки запрошенный url адрес
    }

    // метод для преобразования массива в объект (stdClass Object)
    public static function arrayToObject($data){
        $data_type = gettype($data); // получаем тип переданных данных
        $isObject = $data_type == 'object'; // получаем boolean, является ли данный тип объектом
        $json = json_encode($data); // кодируем данные в json
        return json_decode($json, $isObject); // декодируем json в объект (true - ассоциативный массив)
    }

    protected function getParams(){
        $params = require_once CONF . '/params.php'; // массив параметров (настроек) приложения
        // записываем каждый из параметров в реестр
        if(!empty($params)){
            foreach($params as $k => $v){
                self::$app->setProperty($k, $v);
            }
        }
    }

}
