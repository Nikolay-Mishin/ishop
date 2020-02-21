<?php
// базовый класс контроллера фреймворка - описывает базовые свойства и методы, которые будут наследоваться всеми контроллерами
// работает с данными и видами - обработка полученных из определенного контроллера/модели данных и отображение вида (view) 

namespace ishop\base;

abstract class Controller{
    
    public $route; // массив с маршрутами
    public $controller; // контроллер
    public $model; // модель
    public $view; // вид
    public $prefix; // префикс
    public $layout; // шаблон
    public $data = []; // обычные данные (контент)
    public $meta = ['title' => '', 'desc' => '', 'keywords' => '']; // мета-данные (задаем по умолчанию пустые значения для индексов)
    public $canonical = ''; // каноническая ссылка

    public function __construct($route){
        $this->route = $route;
        $this->controller = $route['controller'];
        $this->model = $route['controller'];
        $this->view = $route['action'];
        $this->prefix = $route['prefix'];
    }

    // получает объект вида и вызывает рендер
    public function getView(){
        $viewObject = new View($this->route, $this->layout, $this->view, $this->meta, $this->canonical); // объект класса Вида
        $viewObject->render($this->data); // вызов метода для рендера и передаем данные из контроллера в вид
    }

    // записывает полученные данные в массив (свойство)
    public function set($data){
        $this->data = $data;
    }

    // задает массив мета-данных
    public function setMeta($title = '', $desc = '', $keywords = ''){
        $this->meta['title'] = h($title); // заголовок
        $this->meta['desc'] = h($desc); // описание
        $this->meta['keywords'] = h($keywords); // ключевые слова
    }

    // задает каноническую ссылку
    public function setCanonical($url = ''){
        $this->canonical = h($url); // каноническая ссылка
    }

    // определяет, каким видом пришел запрос (асинхронно/ajax или нет)
    public function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    // возвращает html-ответ (шаблон/вид) на ajax-запрос
    // внутри отдельного шаблона доступны переменные метода и объект класса ($this - Menu, Filter), где подключается шаблон
    // в видах, подключаемых через loadView() доступен объект класса, в котором был вызван метод ($this - CartController)
    // в видах, подключаемых через класс вида доступен объект класса View ($this - View)
    public function loadView($view, $vars = []){
        extract($vars); // извлекаем переменные из массива
        require APP . "/views/{$this->prefix}{$this->controller}/{$view}.php"; // подключаем вид
        die;
    }

}
