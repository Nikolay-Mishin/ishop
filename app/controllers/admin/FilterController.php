<?php

namespace app\controllers\admin;

use app\models\admin\FilterAttr;
use app\models\admin\FilterGroup;

class FilterController extends AppController {

	// экшен просмотра списка групп фильтров
	public function attributeGroupAction(): void {
		//$attrs_group = \R::findAll('attribute_group'); // получаем список групп фильтров
		$attrs_group = FilterGroup::getAll(); // получаем список групп фильтров
		$this->setMeta('Группы фильтров'); // устанавливаем мета-данные
		$this->set(compact('attrs_group')); // передаем данные в вид
	}

	// экшен удаления групп фильтров
	public function groupDeleteAction(): void {
		//$id = $this->getRequestID(); // получаем id
		//$count = \R::count('attribute_value', 'attr_group_id = ?', [$id]); // считаем число аттрибутов в данной группе фильтров
		//// если есть вложенные фильтры в данной группе, показываем ошибку
		//if ($count) {
		//    $_SESSION['error'] = 'Удаление невозможно, в группе есть аттрибуты';
		//    redirect();
		//}
		//\R::exec('DELETE FROM attribute_group WHERE id = ?', [$id]); // удаляем группу фильтров
		//$_SESSION['success'] = 'Удалено';
		FilterGroup::delete($this->getRequestID()); // удаляем группу фильтров
	}

	// экшен отображения группы фильтров
	public function groupViewAction(): void {
		//$id = $this->getRequestID(); // получаем id
		//$group = \R::load('attribute_group', $id); // получаем данные группы фильтров из БД
		$group = FilterGroup::getById($this->getRequestID()); // получаем данные группы фильтров из БД
		$this->setMeta("Редактирование группы {$group->title}"); // устанавливаем мета-данные
		$this->set(compact('group')); // передаем данные в вид
	}

	// экшен редактирования группы фильтров
	public function groupEditAction(): void {
		// если получены данные из формы, обрабатываем их
		if (!empty($_POST)) {
			//$id = $this->getRequestID(false); // получаем id
			//$group = new FilterGroup(); // объект модели групп фильтров
			//$data = $_POST; // записываем пришедшие данные в переменную
			//$group->load($data); // получаем данные групп фильтров из БД
			//// валидируем данные
			//if (!$group->validate($data)) {
			//    $group->getErrors();
			//    redirect();
			//}
			//// сохраняем данные в БД
			//if ($group->update('attribute_group', $id)) {
			//    $_SESSION['success'] = 'Изменения сохранены';
			//    redirect();
			//}
			new FilterGroup($_POST, [$this->getRequestID()], 'update'); // объект модели группы фильтров
			redirect();
		}
	}

	// экшен добавления групп фильтров
	public function groupAddAction(): void {
		// если получены данные из формы, обрабатываем их
		if (!empty($_POST)) {
			//$group = new FilterGroup(); // объект модели групп фильтров
			//$data = $_POST; // записываем пришедшие данные в переменную
			//$group->load($data); // получаем данные групп фильтров из БД
			//// валидируем данные
			//if (!$group->validate($data)) {
			//    $group->getErrors();
			//    redirect();
			//}
			//// сохраняем данные в БД
			//if ($group->save('attribute_group', false)) {
			//    $_SESSION['success'] = 'Группа добавлена';
			//}
			new FilterGroup($_POST); // объект модели группы фильтров
			redirect();
		}
		$this->setMeta('Новая группа фильтров'); // устанавливаем мета-данные
	}

	// экшен просмотра списка аттрибутов фильтров
	public function attributeAction(): void {
		//// получаем список аттрибутов фильтров
		//$attrs = \R::getAssoc("SELECT attribute_value.*, attribute_group.title FROM attribute_value JOIN attribute_group ON attribute_group.id = attribute_value.attr_group_id");
		$attrs = FilterAttr::getAll(); // получаем список аттрибутов фильтров
		$this->setMeta('Фильтры'); // устанавливаем мета-данные
		$this->set(compact('attrs')); // передаем данные в вид
	}

	// экшен удаления аттрибутов фильтров
	public function attributeDeleteAction(): void {
		//$id = $this->getRequestID(); // получаем id
		//\R::exec("DELETE FROM attribute_product WHERE attr_id = ?", [$id]); // удаляем фильтр из списка фильтров товаров
		//\R::exec("DELETE FROM attribute_value WHERE id = ?", [$id]); // удаляем фильтр из БД
		//$_SESSION['success'] = 'Удалено';
		FilterAttr::delete($this->getRequestID()); // удаляем аттрибут фильтров
	}

	// экшен редактирования аттрибутов фильтров
	public function attributeViewAction(): void {
		//$id = $this->getRequestID(); // получаем id
		//$attr = \R::load('attribute_value', $id); // получаем данные аттрибутов фильтров из БД
		//$attrs_group = \R::findAll('attribute_group'); // получаем список групп фильтров
		list($attr, $groups) = [FilterAttr::getById($this->getRequestID()), FilterGroup::getAll()];
		$this->setMeta('Редактирование аттрибута'); // устанавливаем мета-данные
		$this->set(compact('attr', 'groups')); // передаем данные в вид
	}

	// экшен редактирования аттрибутов фильтров
	public function attributeEditAction(): void {
		// если получены данные из формы, обрабатываем их
		if (!empty($_POST)) {
			//$id = $this->getRequestID(false); // получаем id
			//$attr = new FilterAttr(); // объект модели аттрибутов фильтров
			//$data = $_POST; // записываем пришедшие данные в переменную
			//$attr->load($data); // получаем данные аттрибутов фильтров из БД
			//// валидируем данные
			//if (!$attr->validate($data)) {
			//    $attr->getErrors();
			//    redirect();
			//}
			//// сохраняем данные в БД
			//if ($attr->update('attribute_value', $id)) {
			//    $_SESSION['success'] = 'Изменения сохранены';
			//    redirect();
			//}
			new FilterAttr($_POST, [$this->getRequestID()], 'update'); // объект модели фильтров
			redirect();
		}
	}

	// экшен добавления аттрибутов фильтров
	public function attributeAddAction(): void {
		// если получены данные из формы, обрабатываем их
		if (!empty($_POST)) {
			//$attr = new FilterAttr(); // объект модели аттрибутов фильтров
			//$data = $_POST; // записываем пришедшие данные в переменную
			//$attr->load($data); // получаем данные аттрибутов фильтров из БД
			//// валидируем данные
			//if (!$attr->validate($data)) {
			//    $attr->getErrors();
			//    redirect();
			//}
			//// сохраняем данные в БД
			//if ($attr->save('attribute_value', false)) {
			//    $_SESSION['success'] = 'Атрибут добавлен';
			//    redirect();
			//}
			new FilterAttr($_POST); // объект модели фильтров
			redirect();
		}
		//$group = \R::findAll('attribute_group'); // получаем данные групп фильтров из БД
		$groups = FilterGroup::getAll(); // получаем данные групп фильтров из БД
		$this->setMeta('Новый фильтр'); // устанавливаем мета-данные
		$this->set(compact('groups')); // передаем данные в вид
	}

}
