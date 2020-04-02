<?php

namespace app\controllers\admin;

use app\models\admin\Currency;

class CurrencyController extends AppController {

	// экшен просмотра списка валют
	public function indexAction(){
		//$currencies = \R::findAll('currency'); // получаем список валют
		$currencies = Currency::updateCourse(); // получаем список валют
		$this->setMeta('Валюты магазина'); // устанавливаем мета-данные
		$this->set(compact('currencies')); // передаем данные в вид
	}

	// экшен удаления валют
	public function deleteAction(){
		//$id = $this->getRequestID(); // получаем id
		//$currency = \R::load('currency', $id); // получаем валюту из БД
		//\R::trash($currency); // удаляем валюту из БД
		//$_SESSION['success'] = "Изменения сохранены";
		Currency::delete($this->getRequestID()); // удаляем валюту из БД
		redirect();
	}

	// экшен редактирования валют
	public function viewAction(){
		//$id = $this->getRequestID(); // получаем id
		//$currency = \R::load('currency', $id); // получаем валюту из БД
		list($currency, $courses, $codeList) = [Currency::getById($this->getRequestID()), Currency::getCourses(), Currency::getCodeList()];
		$this->setMeta("Редактирование валюты {$currency->title}"); // устанавливаем мета-данные
		$this->set(compact('currency', 'courses', 'codeList')); // передаем данные в вид
	}

	// экшен редактирования валют
	public function editAction(){
		// если получены данные из формы, обрабатываем их
		if(!empty($_POST)){
			//$id = $this->getRequestID(false); // получаем id
			//$currency = new Currency(); // объект модели валюты
			//$data = $_POST; // записываем пришедшие данные в переменную
			//$currency->load($data); // получаем данные групп фильтров из БД
			//// конвертируем значения флага базовой валюты для записи в БД
			//$currency->attributes['base'] = $currency->attributes['base'] ? '1' : '0';
			//// валидируем данные
			//if(!$currency->validate($data)){
			//    $currency->getErrors();
			//    redirect();
			//}
			//// сохраняем данные в БД
			//if($currency->update('currency', $id)){
			//    $_SESSION['success'] = "Изменения сохранены";
			//}
			new Currency($_POST, [$this->getRequestID()], 'update'); // объект модели валют
			redirect();
		}
	}

	// экшен добавления валют
	public function addAction(){
		// если получены данные из формы, обрабатываем их
		if(!empty($_POST)){
			//$currency = new Currency(); // объект модели валюты
			//$data = $_POST; // записываем пришедшие данные в переменную
			//$currency->load($data); // получаем данные групп фильтров из БД
			//// конвертируем значения флага базовой валюты для записи в БД
			//$currency->attributes['base'] = $currency->attributes['base'] ? '1' : '0';
			//// валидируем данные
			//if(!$currency->validate($data)){
			//    $currency->getErrors();
			//    redirect();
			//}
			//// сохраняем данные в БД
			//if($currency->save('currency')){
			//    $_SESSION['success'] = 'Валюта добавлена';
			//}
			new Currency($_POST); // объект модели валют
			redirect();
		}
		list($courses, $codeList) = [Currency::getCourses(), Currency::getCodeList()];
		$this->setMeta('Новая валюта'); // устанавливаем мета-данные
		$this->set(compact('courses', 'codeList')); // передаем данные в вид
	}

}
