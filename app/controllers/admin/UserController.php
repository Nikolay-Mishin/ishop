<?php

namespace app\controllers\admin;

use app\models\admin\User;
use app\models\User as BaseUser;

// Контроллер авторизации админа
class UserController extends AppController {

	// экшен просмотра списка пользователей
	public function indexAction(): void {
		// list — Присваивает переменным из списка значения подобно массиву
		//$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // текущая страница пагинации
		//$perpage = 3; // число записей на 1 странице
		//$count = \R::count('user'); // число пользователей
		//$pagination = new Pagination($page, $perpage, $count); // объект пагинации
		//$start = $pagination->getStart(); // иницилизируем объект пагинации
		//$users = \R::findAll('user', "LIMIT $start, $perpage"); // получаем список пользователей для текущей страницы пагинации
		list($users, $pagination) = [User::getAll(), User::$pagination];
		$this->setMeta('Список пользователей'); // устанавливаем мета-данные
		$this->set(compact('users', 'pagination'/*, 'count'*/)); // передаем данные в вид
	}

	// экшен добавления нового пользователя
	public function addAction(): void {
		$this->setMeta('Новый пользователь');
	}

	// экшен отображения данных пользователя
	public function viewAction(): void {
		//$user_id = $this->getRequestID();
		//$user = \R::load('user', $user_id);
  //      $orders = \R::getAll("SELECT `order`.`id`, `order`.`user_id`, `order`.`status`, `order`.`date`, `order`.`update_at`, `order`.`currency`, ROUND(SUM(`order_product`.`price`), 2) AS `sum` FROM `order`
  //JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
  //WHERE user_id = {$user_id} GROUP BY `order`.`id` ORDER BY `order`.`status`, `order`.`id`");
		list($user, $orders, $pagination) = [User::getById($this->getRequestID()), User::$orders, User::$pagination];
		$this->setMeta('Редактирование профиля пользователя');
		$this->set(compact('user', 'orders', 'pagination'));
	}

	// экшен редактирования пользователя
	public function editAction(): void {
		// если данные из формы получены, обрабатываем их
		if (!empty($_POST)) {
			//$id = $this->getRequestID(false); // получаем id
			//$user = new \app\models\admin\User(); // объект модели пользователя
			//$data = $_POST; // записываем пришедшие данные в переменную
			//$user->load($data); // загружаем данные в модель
			////обрабатываем пароль
			//if (!$user->attributes['password']) {
			//    unset($user->attributes['password']);
			//}
			//else {
			//    $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
			//}
			//// валидируем данные
			//if (!$user->validate($data) || !$user->checkUnique()) {
			//    $user->getErrors();
			//    redirect();
			//}
			//// сохраняем данные в БД
			//if ($user->update('user', $id)) {
			//    $_SESSION['success'] = 'Изменения сохранены';
			//}
			new User($_POST, $this->getRequestID(), 'update'); // объект модели пользователя
			redirect();
		}
	}

	// экшен страницы авторизации
	public function loginAdminAction(): void {
		// если данные получены методом POST, обрабатываем их
		if (!empty($_POST)) {
			//$user = new User(); // объект модели пользователя
			////авторизовываем пользователя
			//if (!$user->login(true)) {
			//    $_SESSION['error'] = 'Логин/пароль введены неверно';
			//}
			new BaseUser('login', [], [$_POST, true]); // объект модели пользователя
			// если авторизованный пользователь является админом, перенаправляем на гланую админки
			// иначе направляем на главную сайта
			if (User::isAdmin()) {
				redirect(ADMIN);
			}
			else {
				redirect();
			}
		}
		$this->layout = 'login'; // шаблон страницы авторизации в админке
	}

}
