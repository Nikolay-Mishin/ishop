<?php

namespace app\models\admin;

use app\models\Category as Cat;

class Category extends Cat{

	public function __construct($data, $attrs = [], $action = 'save'){
		// вызов родительского конструктора, чтобы его не затереть (перегрузка методов и свойств)
		parent::__construct($data, $attrs, $action);
		/*
		$this->load($data); // загружаем категорию из БД
		// валидируем данные из формы
		if(!$this->validate($data)){
			$this->getErrors(); // получаем список ошибок
			redirect();
		}
		*/
		// сохраняем данные категории в таблицу БД и получаем id соханенной категории в переменную
		// if($this->update('save')){
		// if($this->update('category', $id)){
		if($this->id){
			self::updateAlias($data['title'], $this->id); // создаем алиас для категории на основе ее названия и id
			$_SESSION['success'] = $action == 'update' ? 'Изменения сохранены' : 'Категория добавлена';
			redirect();
		}
	}

	// получает общее число категорий
	public static function getCount(){
		return \R::count('category');
	}

	// получаем из БД категорию по id
	public static function getById($id){
		return \R::load('category', $id); // получаем данную категорию из БД
	}

	// обновляет алиас категории
	public static function updateAlias($title, $id, $col = 'alias'){
		// создаем алиас для категории на основе ее названия и id
		$alias = self::createAlias('category', $col, $title, $id);
		$cat = self::getById($id); // загружаем из БД бин (bean - структура/свойства объекта) сохраненной категории
		$cat->alias = $alias; // записываем алиас для данной категории
		\R::store($cat); // сохраняем категорию в БД
	}

	// удаляет категорию
	public static function delete($id){
		self::checkDelete($id); // проверяем возможно ли удаление категории
		\R::trash(self::getById($id)); // получаем данную категорию из БД и удаляем
		$_SESSION['success'] = 'Категория удалена';
		redirect();
	}

	// проверяет возможно ли удаление категории
	public static function checkDelete($id){
		$children = self::countChildren($id); // считаем количество вложенных категорий
		$errors = '';
		if($children){
			$errors .= '<li>Удаление невозможно, в категории есть вложенные категории</li>';
		}
		$products = self::countProduct($id); // считаем количество товаров в данной категории
		if($products){
			$errors .= '<li>Удаление невозможно, в категории есть товары</li>';
		}
		if($errors){
			$_SESSION['error'] = "<ul>$errors</ul>";
			redirect();
		}
	}

	// считает количество вложенных категорий
	public static function countChildren($id){
		return \R::count('category', 'parent_id = ?', [$id]);
	}

	// считает количество товаров в данной категории
	public static function countProduct($id){
		return \R::count('product', 'category_id = ?', [$id]);
	}

}
