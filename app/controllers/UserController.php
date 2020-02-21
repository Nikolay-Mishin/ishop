<?php
// Контреллер пользователя - регистрация, авторизация и выход

namespace app\controllers;

use app\models\User; // модель пользователя
use app\models\Breadcrumbs; // модель хлебных крошек
use app\models\admin\User as adminUser; // админская модель пользователя

class UserController extends AppController {

	// регистрция пользователя
	public function signupAction(){
		if(User::checkAuth()) redirect(PATH); // если пользователь уже авторизован перенаправляем на главную
		if(empty($_POST)) referer_url('user/cabinet');
		// если получены данные методом POST, обрабатываем их и регистрируем пользователя
		if(!empty($_POST)){
			$user = new User(); // объект модели пользователя
			$data = $_POST; // данные, пришедшие от пользователя (записываем в переменную, чтобы не работать напрямую с массивом POST)
			$user->load($data); // загружаем (массово) данные в модель (из $data в $user->attributes)
			// если валидация не пройдена, получаем список ошибок и перенаправляем пользователя на текущую страницу
			// проверяем уникальные поля с данными
			if(!$user->validate($data) || !$user->checkUnique()){
				$user->getErrors(); // получаем список ошибок
				$_SESSION['form_data'] = $data; // записываем в сессию данные, чтобы значения заполненных полей не сбрасывались
			}
			else{
				// хэшируем пароль
				// password_hash - хэширует пароль с учетом временной метки (текущей даты)
				$user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
				// сохраняем нового пользователя в БД
				// $user->save('user') - благодаря методу getModelName() имя Модели используется в качестве имени таблицы в БД
				// если имя таблицы не передано, ModelName = TableName в БД (User => user)
				if($user->save()){
					$_SESSION['success'] = 'Пользователь зарегистрирован'; // записываем в сессию сообщение об успешной регистрации
					$user->saveSession(); // записываем в сессиию все данные пользователя, кроме пароля
				}else{
					$_SESSION['error'] = 'Ошибка!'; // записываем в сессию сообщение об успешной регистрации
				}
			}
			$_SESSION['user.errors'] = $user->errors; // ошибки при регистрации (выводить под полями формы)
			redirect($_SESSION['redirect']); // перезапрашиваем страницу
		}
		$breadcrumbs = Breadcrumbs::getBreadcrumbs(null, 'Регистрация'); // хлебные крошки
		$errors = $_SESSION['user.errors'] ?? []; // записываем ошибки регистрации в переменную и передаем ее в вид
		$this->setMeta('Регистрация'); // устанавливаем мета-данные
		$this->set(compact('breadcrumbs', 'errors')); // передаем данные в вид
	}

	// авторизация пользователя
	public function loginAction(){
		if(User::checkAuth()) redirect(PATH); // если пользователь уже авторизован перенаправляем на главную
		if(empty($_POST)) referer_url('user/cabinet');
		// если получены данные методом POST, обрабатываем их и регистрируем пользователя
		if(!empty($_POST)){
			$user = new User(); // объект модели пользователя
			// авторизовываем пользователя и выводим сообщение об успешной/не успешной авторизации
			if($user->login()){
				$_SESSION['success'] = 'Вы успешно авторизованы';
			}else{
				$_SESSION['error'] = 'Логин/пароль введены неверно';
			}
			$_SESSION['user.errors'] = $user->errors; // ошибки при авторизации (выводить под полями формы)
			redirect($_SESSION['redirect']); // перезапрашиваем страницу
		}
		$breadcrumbs = Breadcrumbs::getBreadcrumbs(null, 'Регистрация'); // хлебные крошки
		$errors = $_SESSION['user.errors'] ?? []; // записываем ошибки авторизации в переменную и передаем ее в вид
		$this->setMeta('Вход'); // устанавливаем мета-данные
		$this->set(compact('breadcrumbs', 'errors')); // передаем данные в вид
	}

	// выход для авторизованного пользователя
	public function logoutAction(){
		if(isset($_SESSION['user'])) unset($_SESSION['user']); // если в сессии есть данные пользователя (авторизован), удаляем их
		redirect(); // перезапрашиваем страницу
	}

	// экшен отображения личного кабинета пользователя
	public function cabinetAction(){
		if(!User::checkAuth()) redirect(); // если пользователь не авторизован, перенаправляем на главную
		$this->setMeta('Личный кабинет'); // устанавливаем мета-данные
	}

	// экшен редактирования личных данных пользователя
	public function viewAction(){
		if(!User::checkAuth()) redirect('/user/login'); // если пользователь не авторизован, перенаправляем на страницу авторизации
		$user = $_SESSION['user']; // записываем в переменную данные пользователя
		$this->setMeta('Изменение личных данных'); // устанавливаем мета-данные
		$this->set(compact('user')); // передаем данные в вид
	}

	// экшен редактирования личных данных пользователя
	public function editAction(){
		// если получены данные, обрабатываем их
		if(!empty($_POST)){
			$user = new adminUser(); // админская модель пользователя
			$data = $_POST; // данные, пришедшие от пользователя
			$data['id'] = $_SESSION['user']['id']; // id пользователя
			$data['role'] = $_SESSION['user']['role']; // роль пользователя
			$user->load($data); // загружаем данные в модель
			// если поле с паролем не заполнено, удаляем его из аттрибутов модели
			// иначе хэшируем пароль
			if(!$user->attributes['password']){
				unset($user->attributes['password']);
			}else{
				$user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
			}
			// валидируем данные
			if(!$user->validate($data) || !$user->checkUnique()){
				$user->getErrors();
				redirect();
			}
			// сохраняем изменения данных пользователя в БД
			if($user->update('user', $_SESSION['user']['id'])){
				// перезаписываем данные пользователя в сессии
				foreach($user->attributes as $k => $v){
					if($k != 'password') $_SESSION['user'][$k] = $v;
				}
				$_SESSION['success'] = 'Изменения сохранены';
			}
			redirect();
		}
	}

	// экшен вывода списка заказов пользователя
	public function ordersAction(){
		if(!User::checkAuth()) redirect('user/login'); // если пользователь не авторизован, перенаправляем на страницу авторизации
		// получаем заказы пользователя
		$orders = \R::findAll('order', 'user_id = ? ORDER BY status DESC, id DESC', [$_SESSION['user']['id']]);
		$this->setMeta('История заказов'); // устанавливаем мета-данные
		$this->set(compact('orders')); // передаем данные в вид
	}

	// экшен отображения данных заказа пользователя
	public function orderAction(){
		$order_id = !empty($_GET['id']) ? (int)$_GET['id'] : null; // получаем id заказа
		// получаем данные заказа
		$order = \R::getRow("SELECT `order`.*, `user`.`name`, ROUND(SUM(`order_product`.`price`), 2) AS `sum` FROM `order`
  JOIN `user` ON `order`.`user_id` = `user`.`id`
  JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
  WHERE `order`.`id` = ?
  GROUP BY `order`.`id` ORDER BY `order`.`status`, `order`.`id` LIMIT 1", [$order_id]);
		// если заказ не найден, выбрасываем исключение
		if(!$order){
			throw new \Exception('Страница не найдена', 404);
		}
		$order_products = \R::findAll('order_product', "order_id = ?", [$order_id]);
		$this->setMeta("Заказ №{$order_id}");
		$this->set(compact('order', 'order_products'));
	}

}
