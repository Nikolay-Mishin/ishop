<?php

namespace app\controllers\admin;

use app\models\AppModel;
use app\models\admin\Category;
use ishop\App;

class CategoryController extends AppController {

	// экшен для отображения страницы со списком категорий
	public function indexAction(){
		$this->setMeta('Список категорий');
	}

	// экшен удаления категории
	public function deleteAction(){
		Category::delete($this->getRequestID()); // удаляем категорию
	}

	// экшен отображения данных категории
	public function viewAction(){
		$category = Category::getById($this->getRequestID()); // загружаем категорию из БД
		App::$app->setProperty('parent_id', $category->parent_id); // записываем в реестр id родительской категории
		$this->setMeta("Редактирование категории {$category->title}"); // устанавливаем мета-данные
		$this->set(compact('category')); // передаем данные в вид
	}

	// экшен редактирования категории
	public function editAction(){
		// если данные из формы получены, обрабатываем их
		if(!empty($_POST)){
			new Category($_POST, [$_POST['title']], 'update', [$this->getRequestID(false)]); // объект категории
		}
	}

	// экшен добавления новой категории
	public function addAction(){
		// если данные из формы получены, обрабатываем их
		if(!empty($_POST)){
			new Category($_POST, [$_POST['title']]); // объект категории
		}
		$this->setMeta('Новая категория');
	}

}
