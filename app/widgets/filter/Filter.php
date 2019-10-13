<?php

namespace app\widgets\filter;

use ishop\Cache;

class Filter{

    public $groups;
    public $attrs;
    public $tpl;

    public function __construct(){
        $this->tpl = __DIR__ . '/filter_tpl.php'; // подключаем шаблон виджета фильтров
        $this->run(); // вызываем метод для формирования списка фильтров
    }

    // формирует списки фильтров
    protected function run(){
        $cache = Cache::instance(); // объект кэша
        $this->groups = $cache->get('filter_group'); // получаем группы фильтров из кэша
        // если группы не получены из кэша
        if(!$this->groups){
            $this->groups = $this->getGroups(); // получаем группы фильтров
            $cache->set('filter_group', $this->groups, 30); // кэшируем полученные группы
        }
        $this->attrs = $cache->get('filter_attrs'); // получаем аттрибуты групп фильтров из кэша
        // если аттрибуты групп не получены из кэша
        if(!$this->attrs){
            $this->attrs = $this->getAttrs(); // получаем аттрибуты групп фильтров
            $cache->set('filter_attrs', $this->attrs, 30); // кэшируем полученные аттрибуты групп
        }
        $filters = $this->getHtml(); // получаем html-разметку фильтров
        echo $filters; // выводим фильтры
    }

    // получает html-разметку
    protected function getHtml(){
        ob_start(); // включаем буферизацию
        require $this->tpl; // подключаем шаблон
        return ob_get_clean(); // получаем контент из буфера и очищаем буфер
    }

    // получает список групп фильтров
    protected function getGroups(){
        return \R::getAssoc('SELECT id, title FROM attribute_group');
    }

    // получает спиков аттрибутов фильтров
    protected function getAttrs(){
        $data = \R::getAssoc('SELECT * FROM attribute_value'); // получаем ассоциативный массив аттрибутов фильтров
        $attrs = []; // переменная для хранения сформированного списка аттрибутов
        // формируем ассоциативный массив по id группы аттрибутов
        // внутри каждой группы записываем id аттрибута группы => значение
        foreach($data as $k => $v){
            $attrs[$v['attr_group_id']][$k] = $v['value'];
        }
        return $attrs;
        /*
        array
        (
            [1] => [
                [1] => аттрибут 1
                [2] => аттрибут 2
            ]
            [2] => [
                [3] => аттрибут 3
            ]
        )
        */
    }

    // получает список выбранных фильтров
    public static function getFilter(){
        $filter = null;
        // если в GET-параметрах переданы фильтры, обрабатываем их
        if(!empty($_GET['filter'])){
            $filter = preg_replace("#[^\d,]+#", '', $_GET['filter']); // 
            $filter = trim($filter, ','); // убираем ',' с конца строки
        }
        return $filter;
    }

}