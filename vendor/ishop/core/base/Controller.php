<?php
// базовый класс контроллера фреймворка - описывает базовые свойства и методы, которые будут наследоваться всеми контроллерами
// работает с данными и видами - обработка полученных из определенного контроллера/модели данных и отображение вида (view) 

namespace ishop\base;

abstract class Controller {
    
    public $route; // массив с маршрутами
    public $controller; // контроллер
    public $model; // модель
    public $view; // вид
    public $prefix; // префикс
    public $layout; // шаблон
    public $data = []; // обычные данные (контент)
    public $meta = ['title' => '', 'desc' => '', 'keywords' => '']; // мета-данные (задаем по умолчанию пустые значения для индексов)
    public $canonical = ''; // каноническая ссылка
    public $file_prefix; // префикс
    public $typeFiles = ['style', 'script'];
    public $style = ['init' => [], 'main' => [], 'lib' => []];
    public $script = ['init' => [], 'main' => [], 'lib' => []];

    public function __construct($route){
        $this->route = $route;
        $this->controller = $route['controller'];
        $this->model = $route['controller'];
        $this->view = $route['action'];
        $this->prefix = $route['prefix'];
        $this->file_prefix = rtrim($this->prefix, '\\') . ($this->prefix ? '_' : ''); // вырезаем из конца строки '\'
        $this->setFiles($this->typeFiles);
        //$this->setStyle();
        //$this->setScript();
    }

    // получает объект вида и вызывает рендер
    public function getView(){
        $viewObject = new View($this->route, $this->layout, $this->view, $this->meta, $this->canonical, $this); // объект класса Вида
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

    public function setStyle($styles = []){
        $this->setFiles('style', $styles); // подключаем файл конфигурации стилей
    }

    public function setScript($scripts = []){
        $this->setFiles('script', $scripts); // подключаем файл конфигурации скриптов
        
    }

    public function setFiles($types = 'style', $files = []){
        list($types, $files) = [toArray($types), toArray($files)];
        foreach($types as $type){
            $this->$type = require_once CONF . "/{$this->file_prefix}{$type}s.php"; // подключаем файл конфигурации
            debug($this->$type);
            $files_list = '';
            if(!empty($files[$type])){
                foreach($files[$type] as $file_list){
                    $files_list .= $this->getFilesList($file_list, $type) . PHP_EOL;
                }
            }
            else{
                $files_list .= $this->getFilesList($files, $type) . PHP_EOL;
            }
            $files_list .= $this->getFilesList($this->$type, $type) . PHP_EOL;
            debug($files_list);
        }
    }

    protected function getFilesList($files, $type_file){
        $files_list = '';
        foreach($files as $type => $file_list){
            $file_list = toArray($file_list);
            foreach($file_list as $file){
                if($type === 'init' && preg_match('/^(@\s+)(.+)$/', $file, $match)){
                    $file = $match[2] ?? $file;
                    $files_list .= (require_once $file) . PHP_EOL;
                }
                else{
                    if($type_file == 'script'){
                        $file = 'script src="js/'.$file.'.js" /script';
                    }
                    else{
                        $file = 'link href="css/'.$file.'.css" rel="stylesheet" type="text/css" media="all" /';
                    }
                    $files_list .= $file . PHP_EOL;
                }
            }
        }
        return $files_list;
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
