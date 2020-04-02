<?php
// Контреллер пользователя - регистрация, авторизация и выход

namespace app\controllers;

use \Exception;

use app\models\User; // модель пользователя
use app\models\Order; // модель заказа
use app\models\OrderProduct; // модель товаров заказа
use app\models\Breadcrumbs; // модель хлебных крошек
use app\models\admin\User as adminUser; // админская модель пользователя
use app\models\admin\Order as adminOrder; // админская модель заказа

class UserController extends AppController {

	// регистрция пользователя
	public function signupAction(){
		if(User::checkAuth()) redirect(PATH); // если пользователь уже авторизован перенаправляем на главную
		if(empty($_POST)) referer_url('user/cabinet');

		// если получены данные методом POST, обрабатываем их и регистрируем пользователя
		if(!empty($_POST)){
			//$user = new User(); // объект модели пользователя
			//$data = $_POST; // записываем пришедшие данные в переменную
			//$user->load($data); // загружаем данные в модель
			//// валидируем данные
			//if(!$user->validate($data) || !$user->checkUnique()){
			//    $user->getErrors();
			//    $_SESSION['form_data'] = $data;
			//}else{
			//    $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
			//    // сохраняем данные в БД
			//    if($user->save('user')){
			//        $_SESSION['success'] = 'Пользователь зарегистрирован';
			//    }else{
			//        $_SESSION['error'] = 'Ошибка!';
			//    }
			//}

			$user = new User('signup', $_POST); // объект модели пользователя
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
			//$user = new User(); // объект модели пользователя
			//// сохраняем данные в БД
			//if($user->login()){
			//    $_SESSION['success'] = 'Вы успешно авторизованы';
			//}else{
			//    $_SESSION['error'] = 'Логин/пароль введены неверно';
			//}

			// авторизовываем пользователя и выводим сообщение об успешной/не успешной авторизации
			$user = new User('login'); // объект модели пользователя
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
			$data = $_POST; // данные, пришедшие от пользователя
			$data['id'] = $_SESSION['user']['id']; // id пользователя
			$data['role'] = $_SESSION['user']['role']; // роль пользователя
			$user = new adminUser($data, $data['id'], 'update', 'saveSession'); // админская модель пользователя
			redirect();
		}
	}

	// экшен вывода списка заказов пользователя
	public function ordersAction(){
		if(!User::checkAuth()) redirect('user/login'); // если пользователь не авторизован, перенаправляем на страницу авторизации
		// получаем заказы пользователя
		$orders = Order::getByCurrentUserId();
		$this->setMeta('История заказов'); // устанавливаем мета-данные
		$this->set(compact('orders')); // передаем данные в вид
	}

	// экшен отображения данных заказа пользователя
	public function orderAction(){
		$order_id = !empty($_GET['id']) ? (int)$_GET['id'] : null; // получаем id заказа
		// получаем данные заказа
  //      $order = \R::getRow("SELECT `order`.*, `user`.`name`, ROUND(SUM(`order_product`.`price`), 2) AS `sum` FROM `order`
  //JOIN `user` ON `order`.`user_id` = `user`.`id`
  //JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
  //WHERE `order`.`id` = ?
  //GROUP BY `order`.`id` ORDER BY `order`.`status`, `order`.`id` LIMIT 1", [$order_id]);
		$order = adminOrder::getById($order_id); // получаем данные заказа
		// если заказ не найден, выбрасываем исключение
		if(!$order){
			throw new Exception('Страница не найдена', 404);
		}
		$order_products = OrderProduct::getByOrderId($order_id); // получаем данные товаров заказа
		$this->setMeta("Заказ №{$order_id}");
		$this->set(compact('order', 'order_products'));
	}

	// экшен отображения личного кабинета пользователя
	public function profileAction(){
		if(!User::checkAuth()) redirect(); // если пользователь не авторизован, перенаправляем на главную
		$login = $_GET['login'] ?? null;
		$user = User::getByLogin($login);
		if(!$user){
			throw new Exception('Страница не найдена', 404);
		}
		$this->setMeta("Профиль пользователя {$user->login}"); // устанавливаем мета-данные
		$this->set(compact('user'));
	}

}
