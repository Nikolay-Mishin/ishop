<?php

namespace app\models\admin;

use app\models\AppModel;

class Product extends AppModel {

	// переопределяем аттрибуты родительской модели
	public $attributes = [
		'title' => '',
		'category_id' => '',
		'keywords' => '',
		'description' => '',
		'price' => '',
		'old_price' => '',
		'content' => '',
		'status' => '',
		'hit' => '',
		'alias' => '',
	];

	// переопределяем правила валидации формы родительской модели
	public $rules = [
		'required' => [
			['title'],
			['category_id'],
			['price'],
		],
		'integer' => [
			['category_id'],
		],
	];

	// метод изменения товара
	public function editAttrs($id, $data, $table, $attr_id, $condition){
		// получаем аттрибуты товара
		$dataAttrs = \R::getCol("SELECT $attr_id FROM $table WHERE $condition = ?", [$id]);

		// если менеджер убрал связанные товары - удаляем их
		if(empty($data) && !empty($dataAttrs)){
			$this->deleteAttrs($id, $table, $condition); // удаляем связанные товары продукта
			return;
		}

		// если добавляются связанные товары
		if(empty($dataAttrs) && !empty($data)){
			$this->addAttrs($id, $data, $table, $attr_id, $condition); // добавляем товар в БД
			return;
		}

		// если изменились связанные товары - удалим и запишем новые
		if(!empty($data)){
			$result = array_diff($dataAttrs, $data); // возвращает разницу между массивами
			// если есть разница между массивами, удаляем имеющиеся аттрибуты товара и добавляем новые
			if(!empty($result) || count($dataAttrs) != count($data)){
				$this->deleteAttrs($id, $table, $condition); // удаляем аттрибуты товара
				$this->addAttrs($id, $data, $table, $attr_id, $condition); // добавляем товар в БД
			}
		}
	}

	// метод удаления товара
	private function deleteAttrs($id, $table, $condition){
		\R::exec("DELETE FROM $table WHERE $condition = ?", [$id]); // выполняем sql-запрос
	}

	// метод добавления товара
	private function addAttrs($id, $data, $table, $attr_id, $condition){
		$sql_part = ''; // часть sql-запроса
		// формируем sql-запрос
		foreach($data as $v){
			$v = (int)$v;
			$sql_part .= "($id, $v),";
		}
		$sql_part = rtrim($sql_part, ','); // удаляем конечную ','
		\R::exec("INSERT INTO $table ($condition, $attr_id) VALUES $sql_part"); // выполняем sql-запрос
	}

}