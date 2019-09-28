<?php
// виждет меню - гибко настраеваемое меню
/**
 * шаблонность - возможность использовать разные шаблоны (например, в админке другой шаблон для того же меню)
 * передавать класс/таблицу, из которых необходимо брать данные
 * кэшировать/не кэшировать меню
 * и тд
 */

namespace app\widgets\menu;

use ishop\App;
use ishop\Cache;

class Menu{

    protected $data; // данные для меню
    protected $tree; // массив (дерево), который строится из данных
    protected $menuHtml; // html-разметка меню
    protected $tpl; // шаблон для меню
    // protected $tpl = __DIR__ . '/menu_tpl/menu.php'; // в старых версиях php такая запись запрещена
    protected $container = 'ul'; // контейнер для меню (классическое - 'ul', например в админке для выпадающих пунктов - 'select')
    protected $table = 'category'; // таблица в БД, из которой необходимо выбирать данные
    protected $cache = 3600; // время кэширования данных
    protected $cacheKey = 'ishop_menu'; // ключ для сохранения кэша в файл
    protected $attrs = []; // массив дополнительных аттрибутов для меню
    protected $prepend = ''; // для админки

    // заполняет недостающие свойства и получает опции
    public function __construct($options = []){
        // $options - массив опций
        // для сохранения совместимости версий php значение по умолчанию задаем в конструкторе, а не в самом свойстве
        $this->tpl = __DIR__ . '/menu_tpl/menu.php'; // шаблон по умолчанию
        $this->getOptions($options); // получаем опции
        debug($this->table); // распечатываем свойство с именем таблицы
        $this->run(); // формируем меню
    }

    // получает опции
    protected function getOptions($options){
        // если в свойствах класс существует ключ из переданных настроек, то заполняем данное свойство переданным значением
        foreach($options as $k => $v){
            // проверяем существет ли такое свойство у класса
            if(property_exists($this, $k)){
                $this->$k = $v;
            }
        }
    }

    // формирует меню
    protected function run(){
        $cache = Cache::instance(); // получаем объект кэша
        $this->menuHtml = $cache->get($this->cacheKey); // получаем данные html-разметки из кэша по ключу
        // если данные не получены из кэша, то формируем (получаем) их
        if(!$this->menuHtml){
            $this->data = App::$app->getProperty('cats'); // получаем категории из контейнера (реестра)
            // если данные не получены, берем их из БД
            if(!$this->data){
                $this->data = \R::getAssoc("SELECT * FROM {$this->table}");
            }

        }
        $this->output(); // выводим меню
    }

    // метод для вывода меню
    protected function output(){
        echo $this->menuHtml; // выводим html-разметку меню
    }

    // метод для получения дерева, сформированного из ассоциативного массива данных
    protected function getTree(){

    }

    // метод для получения html-разметки на основе дерева и разтелителя
    protected function getMenuHtml($tree, $tab = ''){
        // $tree - дерево
        // $tab - разделитель
        /** когда меню идет не классическое (ul-li), а select (выпадающее меню в виде option)
         * category 1
         * -category 1.1
         * --category 1.1.1
         */
    }

    // метод для формирования куска html-разметки конкретной категории по шаблону
    protected function catToTemplate($category, $tab, $id){
        // $category - категория
        // $tab - разделитель
        // $id - id категории
    }

}