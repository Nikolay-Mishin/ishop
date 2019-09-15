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
            // если передан какой-то шаблон, то берем его, иначе значение константы LAYOUT
            $this->layout = $layout ?: LAYOUT;
        }
    }

    public function render($data){
        $viewFile = APP . "/views/{$this->prefix}{$this->controller}/{$this->view}.php";
        if(is_file($viewFile)){
            ob_start();
            require_once $viewFile;
            $content = ob_get_clean();
        }else{
            throw new \Exception("На найден вид {$viewFile}", 500);
        }
        if(false !== $this->layout){
            $layoutFile = APP . "/views/layouts/{$this->layout}.php";
            if(is_file($layoutFile)){
                require_once $layoutFile;
            }else{
                throw new \Exception("На найден шаблон {$this->layout}", 500);
            }
        }
    }

    public function getMeta(){

    }

}