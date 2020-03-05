<?php

namespace app\widgets\filter;

use ishop\Cache;

class Filter {

	public $groups; // группы фильтров
	public $attrs; // аттрибуты групп фильтров
	public $tpl; // шаблон для формирования списка фильтров
	public $filter; // массив опций фильтра, получаемый из БД

	public function __construct($filter = null, $tpl = ''){
		$this->filter = $filter;
		$this->tpl = $tpl ?: __DIR__ . '/filter_tpl.php'; // подключаем шаблон виджета фильтров
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
		echo $this->getHtml(); // выводим фильтры - получаем html-разметку фильтров
	}

	// получает html-разметку
	protected function getHtml(){
		ob_start(); // включаем буферизацию
		$filter = self::getFilter();
		if(!empty($filter)){
			$filter = explode(',', $filter);
		}
		require $this->tpl; // подключаем шаблон
		return ob_get_clean(); // получаем контент из буфера и очищаем буфер
	}

	// получает список групп фильтров
	protected function getGroups(){
		return \R::getAssoc('SELECT id, title FROM attribute_group'); // SELECT id, title FROM attribute_group
	}

	// получает спиков аттрибутов фильтров
	protected static function getAttrs(){
		// SELECT * FROM attribute_value
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
			$filter = preg_replace("#[^\d,]+#", '', $_GET['filter']); // вырезаем из строки все, кроме цифр и ','
			$filter = trim($filter, ','); // убираем ',' с конца строки (1, => 1)
		}
		return $filter;
	}

	// получает число групп среди отмеченных фильтров
	public static function getCountGroups($filter){
		$filters = explode(',', $filter); // преобразуем строку в массив по разделителю ','
		$cache = Cache::instance(); // объект кэша
		$attrs = $cache->get('filter_attrs'); // получаем аттрибуты фильтров из кэша
		// если аттрибуты не получены из кэша, получаем их из БД
		if(!$attrs){
			$attrs = self::getAttrs();
		}
		$data = []; // массив для сохранения групп отмеченных оттрибутов
		// проходим в цикле по всем группам аттрибутов
		foreach($attrs as $key => $item){
			// проходим по всем аттрибутам данной группы
			foreach($item as $k => $v){
				// если id аттрибута есть в массиве отмеченных аттрибутов, записываем id данной группы в массив и выходим из цикла
				if(in_array($k, $filters)){
					$data[] = $key;
					break;
				}
			}
		}
		return count($data); // возвращаем число элементов массива
		/*
		count($data) = 2; // среди фильтров выбраны аттрибуты из 2 групп
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

}
