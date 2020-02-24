<?php

namespace app\models\admin;

use app\models\AppModel;
use ishop\App;
use ishop\libs\Pagination;

class Product extends AppModel {

	public static $pagination; // пагинация
	public static $filter; // фильтры
	public static $related_product; // связанные товары
	public static $gallery; // галлерея

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

	public function __construct($data = [], $attrs = [], $action = 'save'){
		// устанавливаем необходимые аттрибуты для модели
		$data['status'] = $data['status'] ? '1' : '0';
		$data['hit'] = $data['hit'] ? '1' : '0';
		$this->getImg(); // получаем основную картинку
		// вызов родительского конструктора, чтобы его не затереть (перегрузка методов и свойств)
		parent::__construct($data, $attrs, $action);
		// сохраняем товар в БД
		if($id = $this->id){
			self::updateAlias('product', $data['title'], $this->id); // создаем алиас для категории на основе ее названия и id
			$this->saveGallery($id); // сохраняем галлерею
			// изменяем фильтры товара
			$this->editAttrs($id, $data['attrs'] ?? [], 'attribute_product', 'product_id', 'attr_id');
			// изменяем связанные товары
			$this->editAttrs($id, $data['related'] ?? [], 'related_product', 'product_id', 'related_id');
			$_SESSION['success'] = $action == 'update' ? 'Изменения сохранены' : 'Товар добавлен';
		}
	}

	// получает общее число товаров
	public static function getCount(){
		return \R::count('product');
	}

	// получаем список товаров
	public static function getAll($pagination = true, $perpage = 10){
		/*
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // текущая страница пагинации
		$perpage = 10; // число записей на 1 странице
		$count = \R::count('product'); // число продуктов
		$pagination = new Pagination($page, $perpage, $count, 'product'); // объект пагинации
		$start = $pagination->getStart(); // иницилизируем объект пагинации
		// получаем список продуктов для текущей страницы пагинации
		$products = \R::getAll("SELECT product.*, category.title AS cat FROM product JOIN category ON category.id = product.category_id ORDER BY product.title LIMIT $start, $perpage");
		*/
		self::$pagination = new Pagination(null, $perpage, null, 'product'); // объект пагинации
		// получаем список товаров для текущей страницы пагинации
		return \R::getAll("SELECT product.*, category.title AS cat FROM product JOIN category ON category.id = product.category_id ORDER BY product.title " . self::$pagination->limit);
	}

	// получаем данные товара из БД
	public static function getById($id){
		$product = \R::load('product', $id); // получаем данные товара из БД
		App::$app->setProperty('parent_id', $product->category_id); // сохраняем в реестре id родительской категории
		self::$filter = \R::getCol('SELECT attr_id FROM attribute_product WHERE product_id = ?', [$id]); // получаем фильры товара
		// получаем список связанных товаров
		self::$related_product = \R::getAll("SELECT related_product.related_id, product.title FROM related_product JOIN product ON product.id = related_product.related_id WHERE related_product.product_id = ?", [$id]);
		self::$gallery = \R::getCol('SELECT img FROM gallery WHERE product_id = ?', [$id]); // получаем галлерею
		return $product;
	}

	// удаляет товар
	public static function delete($id){
		\R::exec("DELETE FROM attribute_product WHERE attr_id = ?", [$id]); // удаляем фильтр из списка фильтров товаров
		\R::exec("DELETE FROM attribute_value WHERE id = ?", [$id]); // удаляем фильтр из БД
		$_SESSION['success'] = 'Удалено';
		redirect();
	}

	// метод изменения товара
	public function editAttrs($id, $data, $table, $condition, $attr_id){
		// получаем аттрибуты товара
		$dataAttrs = \R::getCol("SELECT $attr_id FROM $table WHERE $condition = ?", [$id]);

		// если менеджер убрал связанные товары - удаляем их
		if(empty($data) && !empty($dataAttrs)){
			$this->deleteAttrs($id, $table, $condition); // удаляем связанные товары продукта
			return;
		}

		// если добавляются связанные товары
		if(empty($dataAttrs) && !empty($data)){
			debug([$dataAttrs, $data]);
			$this->addAttrs($id, $data, $table, $condition, $attr_id); // добавляем товар в БД
			return;
		}

		// если изменились связанные товары - удалим и запишем новые
		if(!empty($data)){
			$result = array_diff($dataAttrs, $data); // возвращает разницу между массивами
			// если есть разница между массивами, удаляем имеющиеся аттрибуты товара и добавляем новые
			if(!empty($result) || count($dataAttrs) != count($data)){
				$this->deleteAttrs($id, $table, $condition); // удаляем аттрибуты товара
				$this->addAttrs($id, $data, $table, $condition, $attr_id); // добавляем товар в БД
			}
		}
	}

	// метод получения основной картинки
	public function getImg(){
		// если загружена базовая картинка, то в аттрибут 'img' записываем ее и удаляем из сессии
		if(!empty($_SESSION['single'])){
			$this->attributes['img'] = $_SESSION['single'];
			unset($_SESSION['single']);
		}
	}

	// метод сохранения галлереи
	public function saveGallery($id){
		// если загружены картинки галлереи, то в аттрибут 'img' записываем их и удаляем из сессии
		if(!empty($_SESSION['gallery'])){
			/*
			$sql_part = ''; // часть sql-запроса
			// формируем sql-запрос
			foreach($_SESSION['gallery'] as $v){
				$sql_part .= "('$v', $id),";
			}
			$sql_part = rtrim($sql_part, ','); // удаляем конечную ','
			\R::exec("INSERT INTO gallery (img, product_id) VALUES $sql_part"); // выполняем sql-запрос
			*/
			$this->addAttrs($id, $_SESSION['gallery'], 'gallery', 'product_id', 'img');
			unset($_SESSION['gallery']);
		}
	}

	// метод удаления товара
	protected function deleteAttrs($id, $table, $condition){
		\R::exec("DELETE FROM $table WHERE $condition = ?", [$id]); // выполняем sql-запрос
	}

	// метод добавления товара
	protected function addAttrs($id, $data, $table, $condition, $attr_id){
		// если $attr_id - массив, преобразуем его в строку по разделителю
		$attr_id = is_array($attr_id) ? implode(', ', $attr_id) : $attr_id; // [attr_id, title] => 'attr_id, title'

		$sql_part = ''; // часть sql-запроса
		// формируем sql-запрос
		foreach($data as $v){
			// если строка со значением является числом, приводим ее к числу
			// иначе оборачиваем строку в '' для корректности sql-запроса
			$v = ($v === (string)(int)$v) ? (int)$v : "'$v'";
			$sql_part .= "($id, $v),";
		}
		$sql_part = rtrim($sql_part, ','); // удаляем конечную ','
		// debug("INSERT INTO $table ($condition, $attr_id) VALUES $sql_part");
		\R::exec("INSERT INTO $table ($condition, $attr_id) VALUES $sql_part"); // выполняем sql-запрос
	}

}
