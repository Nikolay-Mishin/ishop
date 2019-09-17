<?php
// базовый класс вида фреймворка - описывает базовые свойства и методы, которые будут наследоваться всеми видами
// работает с видами - получение данных из контроллера и отображение контента страницы на основе шаблона и полученных данных

namespace ishop\base;

class View {

    public $route;
    public $controller;
    public $model;
    public $view;
    public $prefix;
    public $layout;
    public $data = [];
    public $meta = []; // массив с мета-тегами, заданными через метод setMeta() в базовом контроллере

    public function __construct($route, $layout = '', $view = '', $meta){
        // $layout - шаблон для отображения (обертка над видом - статичные части сайта - меню, сайдбар, футер и тд)
        // $view - вид для отображения
        $this->route = $route;
        $this->controller = $route['controller'];
        $this->view = $view;
        $this->model = $route['controller'];
        $this->prefix = $route['prefix'];
        $this->meta = $meta;
        // если жёстко передано значение false (подключение шаблона выключено - например, когда контент передан ajax-запросом)
        if($layout === false){
            $this->layout = false;
        }else{
            // если передан какой-то шаблон, то берем его, иначе значение константы LAYOUT
            $this->layout = $layout ?: LAYOUT;
        }
    }

    // рендерит (формирует) страницу для пользователя на основе полученных данных
    public function render($data){
        // если $data - массив, то извлечем данные из массива и сформируем из них соответствующие переменные
        if(is_array($data)) extract($data);
        // prefix - имя префикса (админки)
        // controller - имя папки, в которой лежат соответствующие вызванному контроллеру виды
        // view - имя вида, который должен быть отображен
        $viewFile = APP . "/views/{$this->prefix}{$this->controller}/{$this->view}.php";
        // если такой файл существует - подключаем его, иначе выбрасываем исключение - такой вид не найден
        if(is_file($viewFile)){
            ob_start(); // включаем буферизацию, чтобы вид не выводился
            require_once $viewFile; // подключаем файл вида
            $content = ob_get_clean(); // возвращаем все данные из буфера в переменную и одновременно очистим буфер
        }else{
            throw new \Exception("Не найден вид {$viewFile}", 500);
        }
        // если свойство layout не равно false (подключение шаблона включено)
        if(false !== $this->layout){
            $layoutFile = APP . "/views/layouts/{$this->layout}.php"; // путь к файлу шаблона
            // если такой файл существует - подключаем его, иначе выбрасываем исключение - такой шаблон не найден
            if(is_file($layoutFile)){
                require_once $layoutFile;
            }else{
                throw new \Exception("Не найден шаблон {$this->layout}", 500);
            }
        }
    }

    // возвращает готовую разметку (или массив) с мета-тегами (title, description, keywords)
    public function getMeta(){
        // разметку для вывода в шаблон запишем в переменную и вернем ее
        $output = '<title>' . $this->meta['title'] . '</title>' . PHP_EOL; // PHP_EOL - перенос строк
        $output .= '<meta name="description" content="' . $this->meta['desc'] . '">' . PHP_EOL;
        $output .= '<meta name="keywords" content="' . $this->meta['keywords'] . '">' . PHP_EOL;
        return $output;
    }

}