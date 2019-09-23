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
        foreach($options as $k => $v){
            if(property_exists($this, $k)){
                $this->$k = $v;
            }
        }
    }

    // формирует меню
    protected function run(){
        $cache = Cache::instance();
        $this->menuHtml = $cache->get($this->cacheKey);
        if(!$this->menuHtml){
            $this->data = App::$app->getProperty('cats');
            if(!$this->data){
                $this->data = $cats = \R::getAssoc("SELECT * FROM {$this->table}");
            }

        }
        $this->output();
    }

    protected function output(){
        echo $this->menuHtml;
    }

    protected function getTree(){

    }

    protected function getMenuHtml($tree, $tab = ''){

    }

    protected function catToTemplate($category, $tab, $id){

    }

}