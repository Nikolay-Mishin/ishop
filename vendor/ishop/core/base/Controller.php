<?php
// (абстрактный) базовый класс

namespace ishop\base;

abstract class Controller{

    public $route; // массив с маршрутами
    public $controller;
    public $model;
    public $view;
    public $prefix;
    public $data = []; // обычные данные (контент)
    public $meta = []; // мета-данные

    public function __construct($route){
        $this->route = $route;
        $this->controller = $route['controller'];
        $this->model = $route['controller'];
        $this->view = $route['action'];
        $this->prefix = $route['prefix'];
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