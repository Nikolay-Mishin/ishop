<?php
// базовый класс вида (динамичная часть сайта - центральный контент, меняющий между страницами)

namespace ishop\base;

class View {

    public $route;
    public $controller;
    public $model;
    public $view;
    public $prefix;
    public $layout;
    public $data = [];
    public $meta = [];

    public function __construct($route, $layout = '', $view = '', $meta){
        // $layout - шаблон для отображения (обертка над видом - статичные части сайта - меню, сайдбар, футер и тд)
        // $view - вид для отображения
        $this->route = $route;
        $this->controller = $route['controller'];
        $this->view = $view;
        $this->model = $route['controller'];
        $this->prefix = $route['prefix'];
        $this->meta = $meta;
        // если жёстко передано значение false
        if($layout === false){
            $this->layout = false;
        }else{
            $this->layout = $layout ?: LAYOUT; // если передан какой-то шаблон, то берем его, иначе значение константы LAYOUT
        }
    }

}