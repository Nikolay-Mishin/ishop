<?php

namespace app\models\admin;

use app\models\User as BaseUser;
use app\models\admin\Order; // модель заказа
use ishop\libs\Pagination;
use \Bean;

class User extends BaseUser {

	public static Pagination $pagination; // пагинация
	public static array $orders; // заказы пользователя

	// переопределяем аттрибуты родительской модели
	public array $attributes = [
		'id' => '',
		'login' => '',
		'password' => '',
		'name' => '',
		'email' => '',
		'address' => '',
		'role' => '',
	];

	// переопределяем правила валидации формы родительской модели
	public array $rules = [
		'required' => [
			['login'],
			['name'],
			['email'],
			['role'],
		],
		'email' => [
			['email'],
		],
	];

	public function __construct(array $data = [], array|string|int $attrs = [], string $action = 'save', string $valid = 'checkUnique') {
		if ($data) {
			$valid = toArray($valid, false, 'checkUnique');
			// если пароль не изменен, удаляем его из списка аттрибутов
			// иначе хэшируем полученный пароль
			if (!$data['password']) {
				unset($this->attributes['password']);
			}
			else {
				$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
			}
			// вызов родительского конструктора, чтобы его не затереть (перегрузка методов и свойств)
			// $this->checkUnique()
			//debug(['User', $data, $attrs, $valid]);
			parent::__construct('', $data, $attrs, $action, $valid);
		}
		// сохраняем изменения в БД
		if ($this->id) {
			$_SESSION['success'] = 'Изменения сохранены';
		}
	}

	// получает общее число пользователей
	//public static function getCount() {
	//    return \R::count('user');
	//}

	// получаем список пользователей
	public static function getAll(bool $pagination = true, int $perpage = 3): array {
		/*
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // текущая страница пагинации
		$perpage = 3; // число записей на 1 странице
		$count = \R::count('user'); // число пользователей
		$pagination = new Pagination($page, $perpage, $count); // объект пагинации
		$start = $pagination->getStart(); // иницилизируем объект пагинации
		$users = \R::findAll('user', "LIMIT $start, $perpage"); // получаем список пользователей для текущей страницы пагинации
		*/
		self::$pagination = new Pagination(null, $perpage, null, 'user'); // объект пагинации
		return \R::findAll('user', 'LIMIT '.self::$pagination->limit); // получаем список пользователей для текущей страницы пагинации
	}

	// получаем данные пользователя из БД
	public static function getById(int $id, bool $pagination = true, int $perpage = 3): Bean {
		/*
		// пагинация заказов пользователя
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // текущая страница пагинации
		$perpage = 3; // число записей на 1 странице
		$count = \R::count('order', 'user_id = ?', [$user_id]); // считаем число заказов данного пользователя
		$pagination = new Pagination($page, $perpage, $count); // объект пагинации
		$start = $pagination->getStart(); // иницилизируем объект пагинации
		*/
		self::$pagination = new Pagination(null, $perpage, null, 'user'); // объект пагинации
		self::$orders = Order::getByUserId($id, self::$pagination->limit); // получаем заказы данного пользователя
		return \R::load('user', $id); // загружаем данные пользователя из БД
	}

	// переопределяем метод родительской модели для проверки уникальных полей с данными
	public function checkUnique(): bool {
		// получаем пользователя с соответствующими значениями login или email из аттрибутов
		$user = \R::findOne('user', '(login = ? OR email = ?) AND id <> ?', [$this->attributes['login'], $this->attributes['email'], $this->attributes['id']]);
		// если пользователь найден, то формируем ошибки проверки уникальности
		if ($user) {
			if ($user->login == $this->attributes['login']) {
				$this->errors['unique'][] = 'Этот логин уже занят';
			}
			if ($user->email == $this->attributes['email']) {
				$this->errors['unique'][] = 'Этот email уже занят';
			}
			return false;
		}
		return true;
	}

}
