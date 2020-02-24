<?php

namespace app\controllers\admin;

use app\models\admin\User;
use app\models\User as baseUser;

// Контроллер авторизации админа
class UserController extends AppController {

	// экшен просмотра списка пользователей
	public function indexAction(){
		// list — Присваивает переменным из списка значения подобно массиву
		list($users, $pagination) = [User::getAll(), User::$pagination];
		$this->setMeta('Список пользователей'); // устанавливаем мета-данные
		$this->set(compact('users', 'pagination')); // передаем данные в вид
	}

	// экшен добавления нового пользователя
	public function addAction(){
		$this->setMeta('Новый пользователь');
	}

	// экшен отображения данных пользователя
	public function viewAction(){
		list($user, $orders, $pagination) = [User::getById($this->getRequestID()), User::$orders, User::$pagination];
		$this->setMeta('Редактирование профиля пользователя');
		$this->set(compact('user', 'orders', 'pagination'));
	}

	// экшен редактирования пользователя
	public function editAction(){
		// если данные из формы получены, обрабатываем их
		if(!empty($_POST)){
			new User($_POST, [$this->getRequestID()], 'update'); // объект пользователя
			redirect();
			/*
			$id = $this->getRequestID(false); // получаем id пользователя
			$user = new User(); // объект пользователя
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
			*/
		}
	}

	// экшен страницы авторизации
	public function loginAdminAction(){
		// если данные получены методом POST, обрабатываем их
		if(!empty($_POST)){
			$user = new baseUser(); // объект пользователя
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
