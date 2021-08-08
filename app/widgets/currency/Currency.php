<?php
// виджет валюты

namespace app\widgets\currency;

use ishop\App; // подключаем класс базовый приложения

class Currency {

    protected string $tpl; // шаблон валюты
    protected array $currencies; // список всех доступных валют
    protected array $currency; // текущая валюта

    public function __construct($tpl = '') {
        $this->tpl = $tpl ?: __DIR__ . '/currency_tpl.php'; // путь к шаблону
        $this->run(); // запуск виджета
    }

    // метод запуска виджета - вызывает метод, который строит html-разметку на основе спика валют и текущей валюты
    protected function run(): void {
        $this->currencies = App::$app->getProperty('currencies'); // записываем доступные валюты в свойство виджета
        $this->currency = App::$app->getProperty('currency'); // записываем текущую валюту в свойство виджета
        echo $this->getHtml(); // получаем и выводим html-разметку
    }

    // метод для получения спика доступных валют - сделаны статичными, чтобы каждый раз обращаться к ним не создавая объект класса
    public static function getCurrencies(): array {
        // получаем ассоциативный массив, а не объект (для простоты взаимодействия) - массив индексируется по первому элементу (code)
        // сортируем (ORDER BY) по полю base в обратном порядке (DESC), чтобы первым элементом шла базовая валюта (base = 1)
        // SELECT code, title, symbol_left, symbol_right, value, base FROM currency ORDER BY base DESC
        return \R::getAssoc("SELECT code, title, symbol_left, symbol_right, value, base FROM currency ORDER BY base DESC");
    }

    // метод для получения текущей валюты
    public static function getCurrency(array $currencies): array {
        // проверяем есть ли валюта в куках и содержится ли такой код валюты в списке доступных валют
        // array_key_exists() - проверяет существует ли в массиве $currencies элемент $_COOKIE['currency']
        if (isset($_COOKIE['currency']) && array_key_exists($_COOKIE['currency'], $currencies)) {
            $key = $_COOKIE['currency']; // берем код валюты из кук
        } else {
            $key = key($currencies); // берем базовую валюту из списка доступных валют (возвращает текущий (первый) элемент массива)
        }
        $currency = $currencies[$key]; // текущая валюта из списка доступных (по коду выбранной валюты)
        $currency['code'] = $key; // записываем код текущей валюты в отдельный элемент массива (code)
        return $currency;
    }

    // метод для определения изменения текущей валюты
    public static function checkChangeCurrency(array $curr): ?bool {
        return isset($_SESSION['cart.currency']) ? $curr['value'] !== $_SESSION['cart.currency']['value'] : null;
    }

    // формирует html-разметку
    protected function getHtml(): string {
        ob_start(); // включаем буферизацию
        require_once $this->tpl; // подключаем шаблон
        return ob_get_clean(); // возвращаем контент из буфера и очищаем его
    }

}
