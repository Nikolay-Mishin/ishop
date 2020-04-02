<?php

namespace app\controllers\admin;

use app\models\admin\Product;
use app\models\admin\Modification;
use app\models\Gallery;
use app\models\AppModel;
use ishop\App;

class ProductController extends AppController {

	// экшен отображения списка продуктов
	public function indexAction(){
		//$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // текущая страница пагинации
		//$perpage = 10; // число записей на 1 странице
		//$count = \R::count('product'); // число товаров
		//$pagination = new Pagination($page, $perpage, $count); // объект пагинации
		//$start = $pagination->getStart(); // иницилизируем объект пагинации
		//// получаем список товаров для текущей страницы пагинации
		//$products = \R::getAll("SELECT product.*, category.title AS cat FROM product JOIN category ON category.id = product.category_id ORDER BY product.title LIMIT $start, $perpage");

		list($products, $pagination) = [Product::getAll(), Product::$pagination];
		$this->setMeta('Список товаров'); // устанавливаем мета-данные
		$this->set(compact('products', 'pagination'/*, 'count'*/)); // передаем данные в вид
	}

	// экшен отображения данных товара
	public function viewAction(){
		//$id = $this->getRequestID(); // получаем id
		//$product = \R::load('product', $id); // получаем данные товара из БД
		//App::$app->setProperty('parent_id', $product->category_id); // сохраняем в реестре id родительской категории
		//$filter = \R::getCol('SELECT attr_id FROM attribute_product WHERE product_id = ?', [$id]); // получаем фильры товара
		//// получаем список связанных товаров
		//$related_product = \R::getAll("SELECT related_product.related_id, product.title FROM related_product JOIN product ON product.id = related_product.related_id WHERE related_product.product_id = ?", [$id]);
		//$gallery = \R::getCol('SELECT img FROM gallery WHERE product_id = ?', [$id]); // получаем галлерею

		$values = [Product::getById($this->getRequestID()), Product::$filter, Product::$related_product, Product::$gallery];
		list($product, $filter, $related_product, $gallery) = $values;
		$modifications = Modification::getByProductId($product->id);
		$this->setMeta("Редактирование товара {$product->title}");
		$this->set(compact('product', 'filter', 'related_product', 'gallery', 'modifications'));
	}

	// экшен редактирования товара
	public function editAction(){
		// если данные из формы получены, обрабатываем их
		if(!empty($_POST)){
			//$id = $this->getRequestID(false); // получаем id
			//$product = new Product(); // объект модели товара
			//$data = $_POST; // записываем пришедшие данные в переменную
			//$product->load($data); // получаем данные товара из БД
			////обрабатываем аттрибуты
			//$product->attributes['status'] = $product->attributes['status'] ? '1' : '0';
			//$product->attributes['hit'] = $product->attributes['hit'] ? '1' : '0';
			//$product->getImg(); // получаем основную картинку
			//// валидируем данные
			//if(!$product->validate($data)){
			//    $product->getErrors();
			//    redirect();
			//}
			//// сохраняем данные в БД
			//if($product->update('product', $id)){
			//    $product->editFilter($id, $data); // изменяем фильтры товара
			//    $product->editRelatedProduct($id, $data); // изменяем связанные товары
			//    $product->saveGallery($id); // сохраняем галлерею
			//    // создаем алиас для товара на основе его названия и id
			//    $alias = AppModel::createAlias('product', 'alias', $data['title'], $id);
			//    $product = \R::load('product', $id);
			//    $product->alias = $alias;
			//    \R::store($product);
			//    $_SESSION['success'] = 'Изменения сохранены';
			//}
			new Product($_POST, [$this->getRequestID()], 'update'); // объект товара
			redirect();
		}
	}

	// экшен добавления нового товара
	public function addAction(){
		// если данные из формы получены, обрабатываем их
		if(!empty($_POST)){
			//$product = new Product(); // объект модели товара
			//$data = $_POST; // записываем пришедшие данные в переменную
			//$product->load($data); // получаем данные товара из БД
			////обрабатываем аттрибуты
			//$product->attributes['status'] = $product->attributes['status'] ? '1' : '0';
			//$product->attributes['hit'] = $product->attributes['hit'] ? '1' : '0';
			//$product->getImg(); // получаем основную картинку
			//// валидируем данные
			//if(!$product->validate($data)){
			//    $product->getErrors();
			//    $_SESSION['form_data'] = $data;
			//    redirect();
			//}
			//// сохраняем данные в БД
			//if($id = $product->save('product')){
			//    $product->saveGallery($id); // сохраняем галлерею
			//    $product->editFilter($id, $data); // изменяем фильтры товара
			//    $product->editRelatedProduct($id, $data); // изменяем связанные товары
			//    // создаем алиас для товара на основе его названия и id
			//    $alias = AppModel::createAlias('product', 'alias', $data['title'], $id);
			//    $p = \R::load('product', $id);
			//    $p->alias = $alias;
			//    \R::store($p);
			//    $_SESSION['success'] = 'Товар добавлен';
			//}
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
			//if($_POST['name'] == 'single'){
			//    $wmax = App::$app->getProperty('img_width');
			//    $hmax = App::$app->getProperty('img_height');
			//}else{
			//    $wmax = App::$app->getProperty('gallery_width');
			//    $hmax = App::$app->getProperty('gallery_height');
			//}
			//$name = $_POST['name']; // тип загрузки картинки (single/gallery)
			//$product = new Product(); // объект модели товара
			//$product->uploadImg($name, $wmax, $hmax); // загружаем изображения на сервер
			$wmax = App::$app->getProperty($_POST['name'] == 'single' ? 'img_width' : 'gallery_width');
			$hmax = App::$app->getProperty($_POST['name'] == 'single' ? 'img_height' : 'gallery_height');
			Gallery::uploadImg($_POST['name'], $wmax, $hmax); // загружаем изображения на сервер
		}
	}

	// экшен удаления картинок галлереи
	public function deleteImageAction(){
		$id = isset($_POST['id']) ? $_POST['id'] : null; // id текущего товара
		$src = isset($_POST['src']) ? $_POST['src'] : null; // путь к картинке
		// тип загруженной картинки (single - базовая, gallery - галлерея)
		$upload = isset($_POST['upload']) ? $_POST['upload'] : null;
		// если не получен id или src или тип загруженной картинки, останавливаем работу скрипта
		if(!$src || !$upload){
			return;
		}
		// в зависимости от типа загруженной картинки удаляем базовую картинку либо картинку галлереи
		//if(\R::exec("DELETE FROM gallery WHERE product_id = ? AND img = ?", [$id, $src])){
		//    @unlink(WWW . "/images/$src");
		//    exit('1');
		//}
		$result = $id ? Gallery::{'delete'.upperCamelCase($upload)}($id, $src, Product::getById($id)) : Gallery::deleteImg($src);
		exit("$result");
	}

}
