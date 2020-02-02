<?php

namespace app\controllers\admin;

use app\models\User;

// Контроллер авторизации админа

class UserController extends AppController {

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