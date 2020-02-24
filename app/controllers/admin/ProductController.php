<?php

namespace app\controllers\admin;

use app\models\admin\Product;
use app\models\admin\Gallery;
use app\models\AppModel;
use ishop\App;

class ProductController extends AppController {

	// экшен отображения списка продуктов
	public function indexAction(){
		list($products, $pagination) = [Product::getAll(), Product::$pagination];
		$this->setMeta('Список товаров'); // устанавливаем мета-данные
		$this->set(compact('products', 'pagination')); // передаем данные в вид
	}

	// экшен отображения данных товара
	public function viewAction(){
		list($product, $filter, $related_product, $gallery) = [Product::getById($this->getRequestID()), Product::$filter, Product::$related_product, Product::$gallery];
		$this->setMeta("Редактирование товара {$product->title}");
		$this->set(compact('product', 'filter', 'related_product', 'gallery'));
	}

	// экшен редактирования товара
	public function editAction(){
		// если данные из формы получены, обрабатываем их
		if(!empty($_POST)){
			new Product($_POST, [$this->getRequestID()], 'update'); // объект товара
			redirect();
		}
	}

	// экшен добавления нового товара
	public function addAction(){
		// если данные из формы получены, обрабатываем их
		if(!empty($_POST)){
			new Product($_POST); // объект товара
			redirect();
		}
		$this->setMeta('Новый товар');
	}

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
	// экшен получения списка товаров из поискового запроса
	public function relatedProductAction(){
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
			$wmax = App::$app->getProperty($_POST['name'] == 'single' ? 'img_width' : 'gallery_width');
			$hmax = App::$app->getProperty($_POST['name'] == 'single' ? 'img_height' : 'gallery_height');
			Gallery::uploadImg($_POST['name'], $wmax, $hmax); // загружаем изображения на сервер
		}
	}

	// экшен удаления картинок галлереи
	public function deleteImageAction(){
		$id = isset($_POST['id']) ? $_POST['id'] : null; // id текущего товара
		$src = isset($_POST['src']) ? $_POST['src'] : null; // путь к картинке
		$upload = isset($_POST['upload']) ? $_POST['upload'] : null; // тип загруженной картинки (single - базовая, gallery - галлерея)
		// если не получен id или src или тип загруженной картинки, останавливаем работу скрипта
		if(!$src || !$upload){
			return;
		}
		// в зависимости от типа загруженной картинки удаляем базовую картинку либо картинку галлереи
		$result = $id ? Gallery::{'delete'.AppModel::upperCamelCase($upload)}($id, $src) : Gallery::deleteImg($src);
		exit("$result");
	}

}
