<?php

namespace app\controllers\admin;

use app\models\admin\Product;
use app\models\AppModel;
use ishop\App;
use ishop\libs\Pagination;

class ProductController extends AppController {

	// экшен отображения списка продуктов
	public function indexAction(){
		/*
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // текущая страница пагинации
		$perpage = 10; // число записей на 1 странице
		$count = \R::count('product'); // число продуктов
		$pagination = new Pagination($page, $perpage, $count, 'product'); // объект пагинации
		$start = $pagination->getStart(); // иницилизируем объект пагинации
		// получаем список продуктов для текущей страницы пагинации
		$products = \R::getAll("SELECT product.*, category.title AS cat FROM product JOIN category ON category.id = product.category_id ORDER BY product.title LIMIT $start, $perpage");
		*/
		$pagination = new Pagination(null, 10, null, 'product'); // объект пагинации
		// получаем список товаров для текущей страницы пагинации
		$products = \R::getAll("SELECT product.*, category.title AS cat FROM product JOIN category ON category.id = product.category_id ORDER BY product.title $pagination->limit");
		$this->setMeta('Список товаров'); // устанавливаем мета-данные
		$this->set(compact('products', 'pagination')); // передаем данные в вид
	}

	// экшен добавления нового товара
	public function addAction(){
		// если данные из формы получены, обрабатываем их
		if(!empty($_POST)){
			$product = new Product(); // объект товара
			$data = $_POST; // данные из формы
			$product->load($data); // загружаем данные в модель
			// устанавливаем необходимые аттрибуты для модели
			$product->attributes['status'] = $product->attributes['status'] ? '1' : '0';
			$product->attributes['hit'] = $product->attributes['hit'] ? '1' : '0';
			$product->getImg(); // получаем основную картинку

			// валидируем данные
			if(!$product->validate($data)){
				$product->getErrors();
				$_SESSION['form_data'] = $data;
				redirect();
			}

			// сохраняем продукт в БД
			if($id = $product->save('product')){
				$product->saveGallery($id); // сохраняем галлерею
				$alias = AppModel::createAlias('product', 'alias', $data['title'], $id); // создаем алиас товара
				$p = \R::load('product', $id); // загружаем данные товара из БД
				$p->alias = $alias; // записываем алиас в объект товара
				\R::store($p); // сохраняем изменения в БД
				// изменяем фильтры товара
				$product->editAttrs($id, $data['attrs'], 'attribute_product', 'product_id', 'attr_id');
				// изменяем связанные товары
				$product->editAttrs($id, $data['related'], 'related_product', 'product_id', 'related_id');
				$_SESSION['success'] = 'Товар добавлен';
			}
			redirect();
		}

		$this->setMeta('Новый товар');
	}

	// экшен получения списка товаров из поискового запроса
	public function relatedProductAction(){
		/*$data = [
			'items' => [
				[
					'id' => 1,
					'text' => 'Товар 1',
				],
				[
					'id' => 2,
					'text' => 'Товар 2',
				],
			]
		];*/

		$q = isset($_GET['q']) ? $_GET['q'] : ''; // строка запроса
		$data['items'] = []; // массив данных из запроса
		// получаем товары совпадающие по названию со строкой запроса
		$products = \R::getAssoc('SELECT id, title FROM product WHERE title LIKE ? LIMIT 10', ["%{$q}%"]);
		// если получены товары, формируем массив данных
		if($products){
			$i = 0;
			foreach($products as $id => $title){
				$data['items'][$i]['id'] = $id;
				$data['items'][$i]['text'] = $title;
				$i++;
			}
		}
		echo json_encode($data); // переводим данные в формат json
		die;
	}

	// экшен загрузки картинок
	public function addImageAction(){
		// если есть загружаемые файлы, обрабатываем их
		if(isset($_GET['upload'])){
			// устанавливаем max значения ширины и высоты изображений в зависимости от того какие картинки пришли
			// (основная - 'single' или галлереи)
			// получаем необходимые значения из контейнера приложения
			if($_POST['name'] == 'single'){
				$wmax = App::$app->getProperty('img_width');
				$hmax = App::$app->getProperty('img_height');
			}else{
				$wmax = App::$app->getProperty('gallery_width');
				$hmax = App::$app->getProperty('gallery_height');
			}
			$name = $_POST['name']; // имя файла
			$product = new Product(); // объект товара
			$product->uploadImg($name, $wmax, $hmax); // загружаем изображения на сервер
		}
	}

}