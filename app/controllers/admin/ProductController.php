<?php

namespace app\controllers\admin;

use app\models\admin\Product;
use app\models\AppModel;
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
		// получаем список продуктов для текущей страницы пагинации
		$products = \R::getAll("SELECT product.*, category.title AS cat FROM product JOIN category ON category.id = product.category_id ORDER BY product.title $pagination->limit");
		$this->setMeta('Список товаров'); // устанавливаем мета-данные
		$this->set(compact('products', 'pagination')); // передаем данные в вид
	}

	// экшен добавления нового продукта
	public function addAction(){
		// если данные из формы получены, обрабатываем их
		if(!empty($_POST)){
			$product = new Product(); // объект продукта
			$data = $_POST; // данные из формы
			$product->load($data); // загружаем данные в модель
			// устанавливаем необходимые аттрибуты для модели
			$product->attributes['status'] = $product->attributes['status'] ? '1' : '0';
			$product->attributes['hit'] = $product->attributes['hit'] ? '1' : '0';

			// валидируем данные
			if(!$product->validate($data)){
				$product->getErrors();
				$_SESSION['form_data'] = $data;
				redirect();
			}

			// сохраняем продукт в БД
			if($id = $product->save('product')){
				$alias = AppModel::createAlias('product', 'alias', $data['title'], $id); // создаем алиас продукта
				$p = \R::load('product', $id); // загружаем данные продукта из БД
				$p->alias = $alias; // записываем алиас в объект продукта
				\R::store($p); // сохраняем изменения в БД
				$product->editFilter($id, $data); // изменяем фильтры продукта
				$_SESSION['success'] = 'Товар добавлен';
			}
			redirect();
		}

		$this->setMeta('Новый товар');
	}

}