<?php
// виджет валюты

namespace app\widgets\currency;

class Currency{

    protected $tpl; // шаблон валюты
    protected $currencies; // список всех доступных валют
    protected $currency; // текущая валюта

    public function __construct(){
        $this->tpl = __DIR__ . '/currency_tpl/currency.php'; // путь к шаблону
        $this->run(); // запуск виджета
    }

    // метод запуска виджета - вызывает метод, который строит html-разметку на основе спика валют и текущей валюты
    protected function run(){

        $this->getHtml(); // получаем html-разметку
    }

    // метод для получения спика доступных валют - сделаны статичными, чтобы каждый раз обращаться к ним не создавая объект класса
    public static function getCurrencies(){
        // получаем ассоциативный массив, а не объект (для простоты взаимодействия) - массив индексируется по первому элементу (code)
        // сортируем (ORDER BY) по полю base в обратном порядке (DESC), чтобы первым элементом шла базовая валюта (base = 1)
        return \R::getAssoc("SELECT code, title, symbol_left, symbol_right, value, base FROM currency ORDER BY base DESC");
    }

    // метод для получения текущей валюты
    public static function getCurrency($currencies){
        // проверяем есть ли валюта в куках и содержится ли такой код валюты в списке доступных валют
        // array_key_exists() - проверяет существует ли в массиве $currencies элемент $_COOKIE['currency']
        if(isset($_COOKIE['currency']) && array_key_exists($_COOKIE['currency'], $currencies)){
            $key = $_COOKIE['currency']; // берем код валюты из кук
        }else{
            $key = key($currencies); // берем базовую валюту из списка доступных валют (возвращает текущий (первый) элемент массива)
        }
        $currency = $currencies[$key]; // текущая валюта из списка доступных (по коду выбранной валюты)
        $currency['code'] = $key; // записываем код текущей валюты в отдельный элемент массива (code)
        return $currency;
    }

    // формирует html-разметку
    protected function getHtml(){

    }

}