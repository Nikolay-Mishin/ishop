<?php

namespace app\models\admin;

use app\models\AppModel;

class FilterAttr extends AppModel {

	protected string $table = 'attribute_value';

	// переопределяем аттрибуты родительской модели
	public array $attributes = [
		'value' => '',
		'attr_group_id' => '',
	];

	// переопределяем правила валидации формы родительской модели
	public array $rules = [
		'required' => [
			['value'],
			['attr_group_id'],
		],
		'integer' => [
			['attr_group_id'],
		]
	];

	public function __construct(array $data = [], array $attrs = [], string $action = 'save') {
		// вызов родительского конструктора, чтобы его не затереть (перегрузка методов и свойств)
		parent::__construct($data, $attrs, $action);
		// сохраняем валюту в БД
		if ($this->id) {
			$_SESSION['success'] = $action == 'update' ? 'Изменения сохранены' : 'Аттрибут добавлена';
		}
	}

	// получает общее число аттрибутов фильтров
	//public static function getCount() {
	//    return \R::count('attribute_value');
	//}

	// получаем список аттрибутов фильтров
	public static function getAll(): array {
		return \R::getAssoc("SELECT attribute_value.*, attribute_group.title FROM attribute_value JOIN attribute_group ON attribute_group.id = attribute_value.attr_group_id");
	}

	// получаем данные аттрибутов фильтров из БД
	//public static function getById($id) {
	//    return \R::load('attribute_value', $id);
	//}

	// удаляет аттрибут фильтров
	public static function delete(int $id): void {
		\R::exec("DELETE FROM attribute_product WHERE attr_id = ?", [$id]); // удаляем фильтр из списка фильтров товаров
		\R::exec("DELETE FROM attribute_value WHERE id = ?", [$id]); // удаляем фильтр из БД
		$_SESSION['success'] = 'Удалено';
		redirect();
	}

}
