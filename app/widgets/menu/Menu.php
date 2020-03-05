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
use RedUNIT\Base\Threeway;

class Menu {

    protected $data; // данные для меню
    protected $tree; // массив (дерево), который строится из данных
    protected $menuHtml; // html-разметка меню
    protected $tpl; // шаблон для меню
    // protected $tpl = __DIR__ . '/menu_tpl/menu.php'; // в старых версиях php такая запись запрещена
    protected $container = 'ul'; // контейнер для меню (классическое - 'ul', например в админке для выпадающих пунктов - 'select')
    protected $class = 'menu'; // класс по умолчанию
    protected $table = 'category'; // таблица в БД, из которой необходимо выбирать данные
    protected $cache = 3600; // время кэширования данных
    protected $cacheKey = 'ishop_menu'; // ключ для сохранения кэша в файл
    protected $attrs = []; // массив дополнительных аттрибутов для меню
    protected $prepend = ''; // для админки (когда работаем с select можно вставить option - 'выберите значение')

    // заполняет недостающие свойства и получает опции
    public function __construct($options = []){
        // $options - массив опций
        // для сохранения совместимости версий php значение по умолчанию задаем в конструкторе, а не в самом свойстве
        $this->tpl = __DIR__ . '/menu_tpl/menu.php'; // шаблон по умолчанию
        $this->getOptions($options); // получаем опции
        // debug($this->table); // распечатываем свойство с именем таблицы
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
            $this->tree = $this->getTree(); // получаем дерево
            $this->menuHtml = $this->getMenuHtml($this->tree); // получаем html-разметку и передаем дерево для ее формирования
            // кэшируем меню, если включено кэширование
            if($this->cache){
                $cache->set($this->cacheKey, $this->menuHtml, $this->cache);
            }
        }
        $this->output(); // выводим меню
    }

    // метод для вывода меню
    protected function output(){
        // echo $this->menuHtml; // выводим html-разметку меню
        // формируем список аттрибутов (data-attr='value', style='' и тд)
        $attrs = '';
        if(!empty($this->attrs)){
            foreach($this->attrs as $k => $v){
                $attrs .= " $k='$v' ";
            }
        }
        // оборачиваем html-разметку в контейнер ('ul') + добавляем класс и аттрибуты
        echo "<{$this->container} class='{$this->class}' $attrs>";
            echo $this->prepend;
            echo $this->menuHtml;
        echo "</{$this->container}>";
    }

    // метод для получения дерева, сформированного из ассоциативного массива данных
    // код взят из бесплатного курса по созданию фреймворка (урок 2-9 + 1 урок из премиум курса - создание каталога товара)
    protected function getTree(){
        $tree = []; // массив для хранения дерева
        $data = $this->data; // получаем массив данных
        foreach ($data as $id => &$node) {
            // если parent_id = 0 - это корневой элемент (нет родителя) - помещаем в корень
            if (!$node['parent_id']){
                $tree[$id] = &$node;
            }else{
                // в элементе с parent_id создаем элемент (childs) и помещаем в него дочерние элементы (ветки)
                $data[$node['parent_id']]['childs'][$id] = &$node;
            }
        }
        return $tree;
    }
 
    /**
     * метод для получения html-разметки на основе дерева и разтелителя
     * код взят из бесплатного курса по созданию фреймворка
     * передаем дерево, а не работаем с ним внутри метода, чтобы рекурсивно формировать html-разметку на основе шаблона
     * для формирования html-разметки потомков */ 
    protected function getMenuHtml($tree, $tab = ''){
        // $tree - дерево
        // $tab - разделитель
        /** когда меню идет не классическое (ul-li), а select (выпадающее меню в виде option)
         * category 1
         * -category 1.1
         * --category 1.1.1
         */
        $str = '';
        // $category - категория (ветка массива - участок дерева)
        // рекурсивно формируем html-разметку на основе шаблона
        foreach($tree as $id => $category){
            $str .= $this->catToTemplate($category, $tab, $id);
        }
        return $str;
    }

    // метод для формирования куска html-разметки конкретной категории по шаблону
    // код взят из бесплатного курса по созданию фреймворка
    protected function catToTemplate($category, $tab, $id){
        // $category - категория (участок дерева)
        // $tab - разделитель
        // $id - id категории
        ob_start(); // включаем буферизацию
        require $this->tpl; // подключаем шаблон меню
        return ob_get_clean(); // возвращаем данные из буфера
    }

}
