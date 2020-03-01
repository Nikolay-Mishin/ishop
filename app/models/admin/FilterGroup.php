<?php

namespace app\models\admin;

use app\models\AppModel;

class FilterGroup extends AppModel {

	protected static $table = 'attribute_group';

	// переопределяем аттрибуты родительской модели
	public $attributes = [
		'title' => '',
	];

	// переопределяем правила валидации формы родительской модели
	public $rules = [
		'required' => [
			['title'],
		],
	];

	public function __construct($data = [], $attrs = [], $action = 'save'){
		// вызов родительского конструктора, чтобы его не затереть (перегрузка методов и свойств)
		parent::__construct($data, $attrs, $action);
		// сохраняем группу фильтров в БД
		if($this->id){
			$_SESSION['success'] = $action == 'update' ? 'Изменения сохранены' : 'Группа добавлена';
		}
	}

	// получает общее число групп фильтров
	//public static function getCount(){
	//    return \R::count('attribute_group');
	//}

	// считаем число аттрибутов в данной группе фильтров
	public static function getAttrsInGroup($id){
		return \R::count('attribute_value', 'attr_group_id = ?', [$id]);
	}

	// получаем список групп фильтров
	//public static function getAll(){
	//    return \R::findAll('attribute_group');
	//}

	// получаем данные группы фильтров из БД
	//public static function getById($id){
	//    return \R::load('attribute_group', $id);
	//}

	// удаляет группу фильтров
	public static function delete($id){
		$count = self::getAttrsInGroup($id); // считаем число аттрибутов в данной группе фильтров
		// если есть вложенные фильтры в данной группе, показываем ошибку
		if($count){
			$_SESSION['error'] = 'Удаление невозможно, в группе есть аттрибуты';
			redirect();
		}
		\R::exec('DELETE FROM attribute_group WHERE id = ?', [$id]); // удаляем группу фильтров
		$_SESSION['success'] = 'Удалено';
		redirect();
	}

}
