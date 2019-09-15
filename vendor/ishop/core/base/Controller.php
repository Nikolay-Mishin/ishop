<?php
// (абстрактный) базовый класс

namespace ishop\base;

abstract class Controller{
    // массив с маршрутами
    public $route;
    public $controller;
    public $model;
    public $view;
    public $prefix;
    public $layout;
    // обычные данные (контент)
    public $data = [];
    // мета-данные
    public $meta = [];

    public function __construct($route){
        $this->route = $route;
        $this->controller = $route['controller'];
        $this->model = $route['controller'];
        $this->view = $route['action'];
        $this->prefix = $route['prefix'];
    }

    public function getView(){
        $viewObject = new View($this->route, $this->layout, $this->view, $this->meta);
        $viewObject->render($this->data);
    }

    // записывает полученные данные в массив (свойство)
    public function set($data){
        $this->data = $data;
    }

    // задает массив мета-данных
    public function setMeta($title = '', $desc = '', $keywords = ''){
        $this->meta['title'] = $title;
        $this->meta['desc'] = $desc;
        $this->meta['keywords'] = $keywords;
    }

}