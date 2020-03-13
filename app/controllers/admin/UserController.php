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
			new User($_POST, $this->getRequestID(), 'update'); // объект пользователя
			redirect();
		}
	}

	// экшен страницы авторизации
	public function loginAdminAction(){
		// если данные получены методом POST, обрабатываем их
		if(!empty($_POST)){
			$user = new baseUser('login', [], [$_POST, true]); // объект пользователя
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
