<?php

namespace app\controllers\admin;

use app\models\User;
use ishop\libs\Pagination;

// Контроллер авторизации админа
class UserController extends AppController {

	// экшен просмотра списка пользователей
	public function indexAction(){
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // текущая страница пагинации
		$perpage = 3; // число записей на 1 странице
		$count = \R::count('user'); // число пользователей
		$pagination = new Pagination($page, $perpage, $count); // объект пагинации
		$start = $pagination->getStart(); // иницилизируем объект пагинации
		$users = \R::findAll('user', "LIMIT $start, $perpage"); // получаем список пользователей для текущей страницы пагинации
		$this->setMeta('Список пользователей'); // устанавливаем мета-данные
		$this->set(compact('users', 'pagination', 'count')); // передаем данные в вид
	}

	// экшен добавления нового пользователя
	public function addAction(){
		$this->setMeta('Новый пользователь');
	}

	// экшен редактирования пользователя
	public function viewAction(){
		$user_id = $this->getRequestID(); // получаем id пользователя
		$user = \R::load('user', $user_id); // загружаем данные пользователя из БД

		// пагинация заказов пользователя
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // текущая страница пагинации
		$perpage = 3; // число записей на 1 странице
		$count = \R::count('order', 'user_id = ?', [$user_id]); // считаем число заказов данного пользователя
		$pagination = new Pagination($page, $perpage, $count); // объект пагинации
		$start = $pagination->getStart(); // иницилизируем объект пагинации

		// получаем заказы данного пользователя
		$orders = \R::getAll("SELECT `order`.`id`, `order`.`user_id`, `order`.`status`, `order`.`date`, `order`.`update_at`, `order`.`currency`, ROUND(SUM(`order_product`.`price`), 2) AS `sum` FROM `order`
  JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
  WHERE user_id = {$user_id} GROUP BY `order`.`id` ORDER BY `order`.`status`, `order`.`id` LIMIT $start, $perpage");
		
		$this->setMeta('Редактирование профиля пользователя');
		$this->set(compact('user', 'orders', 'pagination', 'count'));
	}

	// экшен редактирования пользователя
	public function editAction(){
		// если данные из формы получены, обрабатываем их
		if(!empty($_POST)){
			$id = $this->getRequestID(false); // получаем id пользователя
			$user = new \app\models\admin\User(); // объект пользователя
			$data = $_POST; // даные из формы
			$user->load($data); // загружаем данные из формы в объект
			// если пароль не изменен, удаляем его из списка аттрибутов
			// иначе хэшируем полученный пароль
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
			// сохраняем изменения в БД
			if($user->update($id)){
				$_SESSION['success'] = 'Изменения сохранены';
			}
			redirect();
		}
	}

	// экшен страницы авторизации
	public function loginAdminAction(){
		// если данные получены методом POST, обрабатываем их
		if(!empty($_POST)){
			$user = new User(); // объект пользователя
			// при авторизации указываем флаг true для проверки роли пользователя (isAdmin)
			if(!$user->login(true)){
				$_SESSION['error'] = 'Логин/пароль введены неверно';
			}
			// если авторизованный пользователь является админом, перенаправляем на гланую админки
			// иначе направляем на главную сайта
			if(User::isAdmin()){
				redirect(ADMIN);
			}else{
				redirect();
			}
		}
		$this->layout = 'login'; // шаблон страницы авторизации в админке
	}

}
