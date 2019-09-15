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
            // имя контроллера (путь), который будет вызван + постфикс (MainController)
            $controller = 'app\controllers\\' . self::$route['prefix'] . self::$route['controller'] . 'Controller';
            // проверяем существует ли такой класс
            if(class_exists($controller)){
                $controllerObject = new $controller(self::$route); // объект контроллера
                $action = self::lowerCamelCase(self::$route['action']) . 'Action'; // имя экшена (indexAction)
                // проверяем существует ли такой метод у данного класса
                if(method_exists($controllerObject, $action)){
                    $controllerObject->$action(); // вызываем экшен у заданного класса
                }else{
                    throw new \Exception("Метод $controller::$action не найден", 404);
                }
            }else{
                throw new \Exception("Контроллер $controller не найден", 404);
            }
        }else{
            throw new \Exception("Страница не найдена", 404);
        }
    }

    // принимает запрошенный url адрес и ищет соответствие в маршрутах
    public static function matchRoute($url){
        foreach(self::$routes as $pattern => $route){
            // ищем совпадения шаблона $pattern в $url и записываем результат в $matches
            if(preg_match("#{$pattern}#", $url, $matches)){
                foreach($matches as $k => $v){
                    // если ключ - строка, записываем в массив $route ключ-значение из $matches
                    if(is_string($k)){
                        $route[$k] = $v;
                    }
                }
                // action по умолчанию = index
                if(empty($route['action'])){
                    $route['action'] = 'index';
                }
                // prefix по умолчанию = ''
                if(!isset($route['prefix'])){
                    $route['prefix'] = '';
                }else{
                    $route['prefix'] .= '\\'; // добавляем обратный слэш (2 слэш - экранирование)
                }
                // меняем имя контроллера для вызова
                $route['controller'] = self::upperCamelCase($route['controller']);
                self::$route = $route; // записываем результат в текущий маршрут
                return true;
            }
        }
        return false;
    }

    // CamelCase - для изменения имен контроллеров (каждое слово в верхнем регистре)
    protected static function upperCamelCase($name){
        // page-new => page new => Page New => PageNew
        // - заменяем на пробел => ucwords - каждое слово делает с заглавной буквы => заменяем пробел на пустую строку
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));
    }

    // camelCase - для изменения имен экшенов (первый символ в нижнем регистре)
    protected static function lowerCamelCase($name){
        return lcfirst(self::upperCamelCase($name));
    }

}