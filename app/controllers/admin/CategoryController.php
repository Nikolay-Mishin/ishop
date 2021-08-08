<?php

namespace app\controllers\admin;

use app\models\admin\Category;
use ishop\App;

class CategoryController extends AppController {

	// экшен для отображения страницы со списком категорий
	public function indexAction(): void {
		$this->setMeta('Список категорий');
	}

	// экшен удаления категории
	public function deleteAction(): void {
		//$id = $this->getRequestID(); // получаем id
		//$children = \R::count('category', 'parent_id = ?', [$id]); // считаем количество вложенных категорий
		//$errors = '';
		//if($children){
		//    $errors .= '<li>Удаление невозможно, в категории есть вложенные категории</li>';
		//}
		//$products = \R::count('product', 'category_id = ?', [$id]); // считаем количество товаров в данной категории
		//if($products){
		//    $errors .= '<li>Удаление невозможно, в категории есть товары</li>';
		//}
		//if($errors){
		//    $_SESSION['error'] = "<ul>$errors</ul>";
		//    redirect();
		//}
		//$category = \R::load('category', $id); // получаем данную категорию из БД
		//\R::trash($category); // удаляем категорию из БД
		//$_SESSION['success'] = 'Категория удалена';
		Category::delete($this->getRequestID()); // удаляем категорию
	}

	// экшен отображения данных категории
	public function viewAction(): void {
		//$id = $this->getRequestID(); // получаем id
		//$category = \R::load('category', $id); // загружаем категорию из БД
		$category = Category::getById($this->getRequestID()); // загружаем категорию из БД
		App::$app->setProperty('parent_id', $category->parent_id); // записываем в реестр id родительской категории
		$this->setMeta("Редактирование категории {$category->title}"); // устанавливаем мета-данные
		$this->set(compact('category')); // передаем данные в вид
	}

	// экшен редактирования категории
	public function editAction(): void {
		// если данные из формы получены, обрабатываем их
		if (!empty($_POST)) {
			//$id = $this->getRequestID(false); // получаем id
			//$category = new Category(); // объект модели категории
			//$data = $_POST; // записываем пришедшие данные в переменную
			//$category->load($data); // загружаем категорию из БД
			//// валидируем данные из формы
			//if(!$category->validate($data)){
			//    $category->getErrors();
			//    redirect();
			//}
			//// сохраняем данные категории в таблицу БД
			//if($category->update('category', $id)){
			//    // создаем алиас для товара на основе его названия и id
			//    $alias = AppModel::createAlias('category', 'alias', $data['title'], $id);
			//    $category = \R::load('category', $id);
			//    $category->alias = $alias;
			//    \R::store($category);
			//    $_SESSION['success'] = 'Изменения сохранены';
			//}
			new Category($_POST, [$this->getRequestID()], 'update'); // объект модели категории
			redirect();
		}
	}

	// экшен добавления новой категории
	public function addAction(): void {
		// если данные из формы получены, обрабатываем их
		if (!empty($_POST)) {
			//$category = new Category(); // объект модели категории
			//$data = $_POST; // записываем пришедшие данные в переменную
			//$category->load($data); // загружаем категорию из БД
			//// валидируем данные из формы
			//if(!$category->validate($data)){
			//    $category->getErrors();
			//    redirect();
			//}
			//// сохраняем данные категории в таблицу БД и получаем id соханенной категории в переменную
			//if($id = $category->save('category')){
			//    // создаем алиас для товара на основе его названия и id
			//    $alias = AppModel::createAlias('category', 'alias', $data['title'], $id);
			//    $cat = \R::load('category', $id);
			//    $cat->alias = $alias;
			//    \R::store($cat);
			//    $_SESSION['success'] = 'Категория добавлена';
			//}
			new Category($_POST); // объект модели категории
			redirect();
		}
		$this->setMeta('Новая категория');
	}

}
