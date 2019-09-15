<?php
// класс Маршрутизатор - отвечает за обработку запросов и вызов контроллера (controller) и экшена (action)

namespace ishop;

class Router{

    protected static $routes = []; // таблица марштуров - 
    protected static $route = []; // текущий маршрут - если найдено соответствие с адресом в маршрутах

    // записывает правила в таблицу маршрутов
    public static function add($regexp, $route = []){
        // $regexp - шаблон адреса
        // $route (не обязателен) - конкретный контроллер/экшен, который будет соответствовать шаблону
        self::$routes[$regexp] = $route;
    }

    // возвращает таблицу маршрутов
    public static function getRoutes(){
        return self::$routes;
    }

    // возвращает тукещий маршрут
    public static function getRoute(){
        return self::$route;
    }

    // принимает запрошенный url адрес и обрабатывает его
    public static function dispatch($url){
        if(self::matchRoute($url)){
            echo 'OK';
        }else{
            echo 'NO';
        }
    }

    // принимает запрошенный url адрес и ищет соответствие в маршрутах
    public static function matchRoute($url){
        return false;
    }

}