<?php

namespace app\controllers\admin;

use app\models\AppModel;
use app\models\Category;
use ishop\App;

class CategoryController extends AppController {

	// экшен для отображения страницы со списком категорий
	public function indexAction(){
		$this->setMeta('Список категорий');
	}

	// экшен удаления категории
	public function deleteAction(){
		$id = $this->getRequestID(); // получаем id категории
		$children = \R::count('category', 'parent_id = ?', [$id]); // считаем количество вложенных категорий
		$errors = '';
		if($children){
			$errors .= '<li>Удаление невозможно, в категории есть вложенные категории</li>';
		}
		$products = \R::count('product', 'category_id = ?', [$id]); // считаем количество товаров в данной категории
		if($products){
			$errors .= '<li>Удаление невозможно, в категории есть товары</li>';
		}
		if($errors){
			$_SESSION['error'] = "<ul>$errors</ul>";
			redirect();
		}
		$category = \R::load('category', $id); // получаем данную категорию из БД
		\R::trash($category); // удаляем категорию
		$_SESSION['success'] = 'Категория удалена';
		redirect();
	}

	// экшен добавления новой категории
	public function addAction(){
		$this->setMeta('Новая категория');
		// если данные из формы получены, обрабатываем их
		if(!empty($_POST)){
			/*
			$category = new Category(); // объект категории
			$data = $_POST; // данные из формы
			$category->load($data); // загружаем данные из формы в модель категории
			// валидируем данные категории
			if(!$category->validate($data)){
				$category->getErrors(); // получаем список ошибок
				redirect();
			}
			*/
			$data = $_POST; // данные из формы
			$category = new Category($data); // объект категории
			// сохраняем данные категории в таблицу БД и получаем id соханенной категории в переменную
			// if($id = $category->save('category')){
			if($id = $category->last_insert_id){
				// создаем алиас для категории на основе ее названия и id
				$alias = AppModel::createAlias('category', 'alias', $data['title'], $id);
				$category = \R::load('category', $id); // загружаем из БД бин (bean - структура/свойства объекта) сохраненной категории
				$category->alias = $alias; // записываем алиас для данной категории
				\R::store($category); // сохраняем категорию в БД
				$_SESSION['success'] = 'Категория добавлена';
			}
			redirect();
		}
	}

	// экшен редактирования категории
	public function editAction(){
		if(!empty($_POST)){
			$id = $this->getRequestID(false); // получаем id категории для редактирования
			/*
			$category = new Category(); // объект категории
			$data = $_POST; // данные из формы
			$category->load($data); // загружаем категорию из БД
			// валидируем данные из формы
			if(!$category->validate($data)){
				$category->getErrors(); // получаем список ошибок
				redirect();
			}
			*/
			$data = $_POST; // данные из формы
			$category = new Category($data, 'update', [$id]); // объект категории
			// if($category->update('category', $id)){
			if($id = $category->last_insert_id){
				// создаем алиас для категории на основе ее названия и id
				$alias = AppModel::createAlias('category', 'alias', $data['title'], $id);
				$category = \R::load('category', $id); // загружаем из БД бин (bean - структура/свойства объекта) сохраненной категории
				$category->alias = $alias; // записываем алиас для данной категории
				\R::store($category); // сохраняем категорию в БД
				$_SESSION['success'] = 'Изменения сохранены';
			}
			redirect();
		}
		$id = $this->getRequestID(); // получаем id изменяемой категории
		$category = \R::load('category', $id); // загружаем категорию из БД
		App::$app->setProperty('parent_id', $category->parent_id); // записываем в реестр id родительской категории
		$this->setMeta("Редактирование категории {$category->title}"); // устанавливаем мета-данные
		$this->set(compact('category')); // передаем данные в вид
	}

}