<?php
// Модель пользователя

namespace app\models;

class User extends AppModel {

	// аттрибуты модели (параметры/поля формы)
	public $attributes = [
		'login' => '',
		'password' => '',
		'name' => '',
		'email' => '',
		'address' => '',
		'role' => 'user',
	];

	// набор правил для валидации
	public $rules = [
		// обязательные поля
		'required' => [
			['login'],
			['password'],
			['name'],
			['email'],
			['address'],
		],
		// поле email
		'email' => [
			['email'],
		],
		// минимальная длина для поля
		'lengthMin' => [
			['password', 6],
		]
	];

	public function __construct($userAction = 'login', $data = [], $attrs = [], $action = 'save', $valid = []){
		if($userAction == 'login' || $userAction == 'signup'){
			// хэшируем пароль
			// password_hash - хэширует пароль с учетом временной метки (текущей даты)
			$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
			// вызов родительского конструктора, чтобы его не затереть (перегрузка методов и свойств)
			// $this->checkUnique()
			// сохраняем нового пользователя в БД
			// $user->save('user') - благодаря методу getModelName() имя Модели используется в качестве имени таблицы в БД
			// если имя таблицы не передано, ModelName = TableName в БД (User => user)
			parent::__construct($data, $attrs, $action, 'checkUnique');
			return callMethod($this, $userAction, $attrs);
		}
		parent::__construct($data, $attrs, $action, $valid);
	}

	// проверяем роль пользователя и получаем из БД пользователя с соответствующей ролью (admin/user)
	public static function getByLogin($login, $isAdmin = false){
		$role = $isAdmin ? "AND role = 'admin'" : '';
		return \R::findOne('user', "login = ? $role", [$login]);
	}

	// проверяет уникальные поля с данными
	public function checkUnique(){
		// получаем пользователя с соответствующими значениями login или email из аттрибутов
		$user = \R::findOne('user', 'login = ? OR email = ?', [$this->attributes['login'], $this->attributes['email']]);
		// если пользователь найден, то формируем ошибки проверки уникальности
		if($user){
			if($user->login == $this->attributes['login']){
				$this->errors['unique'][] = 'Этот логин уже занят';
			}
			if($user->email == $this->attributes['email']){
				$this->errors['unique'][] = 'Этот email уже занят';
			}
			return false;
		}
		return true;
	}

	// регистрция пользователя
	public function signup($login = true){
		// если изменения сохранены в БД, сохраняем данные пользователя в сессию (авторизовываем)
		if($this->id){
			$_SESSION['success'] = 'Пользователь зарегистрирован'; // записываем в сессию сообщение об успешной регистрации
			return $login ? $this->saveSession() : true; // записываем в сессиию все данные пользователя, кроме пароля
		}
		$_SESSION['error'] = 'Ошибка!'; // записываем в сессию сообщение об успешной регистрации
		$_SESSION['user.errors'] = $this->errors; // ошибки при регистрации (выводить под полями формы)
		return false;
	}

	// авторизация пользователя
	// за авторизацию отвечает 1 метод - и для обычного пользователя, и для админа
	// админ может авторизоваться как со страницы админки, так и со страницы авторизации обычного пользователя
	// пользователь может авторизоваться только на странице авторизации обычного пользователя и получает доступ к личному кабинету
	public function login($isAdmin = false){
		// $isAdmin - является ли пользователь админом
		$login = !empty(trim($_POST['login'])) ? trim($_POST['login']) : null; // получаем логин из формы авторизации
		$password = !empty(trim($_POST['password'])) ? trim($_POST['password']) : null; // получаем пароль из формы авторизации
		if($login && $password){
			// получаем из БД пользователя с соответствующей ролью (admin/user)
			// если пользователь получен, то проверяем правильность введенного пароля
			// password_verify - сравнивает хэш пароля (1 - из формы, 2 - из БД)
			if($user = self::getByLogin($login, $isAdmin)){
				// password_verify - сравнивает хэш пароля (1 - из формы, 2 - из БД)
				if(password_verify($password, $user->password)){
					$_SESSION['success'] = 'Вы успешно авторизованы';
					return $this->saveSession($user); // записываем в сессиию все данные пользователя, кроме пароля
				}
			}
		}
		$_SESSION['error'] = 'Логин/пароль введены неверно';
		$_SESSION['user.errors'] = $this->errors; // ошибки при авторизации (выводить под полями формы)
		return false;
	}

	// сохраняет данные авторизованного пользователя в сессию
	public function saveSession($user = null){
		$user = $user ?? $this->attributes;
		foreach($user as $k => $v){
			if($k != 'password') $_SESSION['user'][$k] = $v;
		}
		return $_SESSION['user'];
	}

	// проверяет авторизован ли пользователь
	public static function checkAuth(){
		return isset($_SESSION['user']);
	}

	// проверяет роль пользователя - админ/не админ
	public static function isAdmin(){
		return (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin');
	}

}
