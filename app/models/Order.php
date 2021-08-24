<?php
// Модель заказа

namespace app\models;

use \Exception;
use \Bean;

use app\models\Payment; // модель оплаты
use ishop\App;

use Swift_Mailer; // класс отправки писем
use Swift_Message; // класс формирования писем
use Swift_SmtpTransport; // класс smtp-сервера

class Order extends AppModel {

	public static $pagination; // пагинация
	public static int $count; // число заказов

	public string $tpl = APP . '/views/mail/mail_order.php'; // шаблон письма

	// аттрибуты модели (параметры/поля формы)
	public array $attributes = [
		'user_id' => '',
		'note' => '',
		'currency' => '',
		'sum' => ''
	];

	public function __construct(array $data) {
		parent::__construct($data); // вызов родительского конструктора, чтобы его не затереть (перегрузка методов и свойств)
		// если заказ сохранен, обрабатываем его
		// id сохраненного заказа
		if ($this->id) {
			$this->saveOrderProduct(); // сохраняем продукты заказа
			// устанавливаем данные для оплаты заказа и отправляем письмо пользователю и администратору/менеджеру
			$this->mailOrder($data['user_email'], Payment::setData($this->id, $data['pay']));
		}
	}

	// получает заказ
	public static function getById(int $id): Bean {
		return \R::load('order', (int) $id);
	}

	// получает заказы пользователя
	public static function getByCurrentUserId(): array {
		return \R::findAll('order', 'user_id = ? ORDER BY status DESC, id DESC', [$_SESSION['user']['id']]);
	}

	// сохраняет оформленный заказ
	// ДЗ - сделать правильное сохранение заказа (с помощью метода save в базовой модели)
	/* public static function saveOrder($data){
		$order = \R::dispense('order'); // создаем запись для сохранения данных в БД
		$order->user_id = $data['user_id']; // id пользователя
		$order->note = $data['note']; // примечание к заказу
		$order->currency = $_SESSION['cart.currency']['code']; // валюта заказа
		$order->sum = $_SESSION['cart.sum']; // сумма заказа
		$order_id = \R::store($order); // сохраняем данные заказа в БД
		self::saveOrderProduct($order_id); // сохраняет продукты данного заказа
		return $order_id;
	} */

	// сохраняет продукты данного заказа
	// public static function saveOrderProduct($order_id)
	public function saveOrderProduct(): void {
		$sql_part = ''; // переменная для хранения sql запроса
		// для каждого товара формируем sql строку значений и разделяем товары через запятую (',')
		foreach ($_SESSION['cart'] as $product_id => $product) {
			$product_id = (int)$product_id; // приводим id товара к числу
			// берем выражение в фигурные скобки {}, чтобы можно было в строке использовать элементы массив и свойства объекта
			$sql_part .= "({$this->id}, $product_id, {$product['qty']}, '{$product['title']}', {$product['price']}),";
		}
		$sql_part = rtrim($sql_part, ','); // удаляем запятую справа - в конце строки (',')
		// выполняем sql запрос
		\R::exec("INSERT INTO order_product (order_id, product_id, qty, title, price) VALUES $sql_part");
	}

	// отправляет письмо с информацией о заказе клиенту и администратору/менеджеру
	public function mailOrder(string $user_email, array $pay): void {
		// $user_email - почта пользователя для отправки письма
		// $pay - checkbox хочет ли пользователь сразу оплатить заказ
		if ($pay) redirect('/payment/pay'); // если пользователь хочет оплатить заказ сразу, перенаправляем его на форму оплаты
		try {
			// Create the Transport
			// создаем объект smtp и передаем параметры для настройки smtp-сервера
			$transport = (new Swift_SmtpTransport(App::$app->getProperty('smtp_host'), App::$app->getProperty('smtp_port'), App::$app->getProperty('smtp_protocol')))
				->setUsername(App::$app->getProperty('smtp_login'))
				->setPassword(App::$app->getProperty('smtp_password'))
			;
			// Create the Mailer using your created Transport
			$mailer = new Swift_Mailer($transport); // создаем объект для отправки писем и передаем ему настройки smtp-сервера

			// Create a message
			// создаем сообщение письма и записываем его в переменную
			// для письма администратору можно создать отдельный шаблон и подключать его ($body_admin)
			$body = self::getFileContents($this->tpl); // получаем контент из буфера

			// письмо для клиента
			// setFrom должно совпадать с setUsername в настройках smtp
			$message_client = (new Swift_Message("Вы совершили заказ №{$this->id} на сайте " . App::$app->getProperty('shop_name')))
				->setFrom([App::$app->getProperty('smtp_login') => App::$app->getProperty('shop_name')]) // от кого
				->setTo($user_email) // кому
				->setBody($body, 'text/html') // тема
			;

			// письмо для администратора
			$message_admin = (new Swift_Message("Сделан заказ №{$this->id}"))
				->setFrom([App::$app->getProperty('smtp_login') => App::$app->getProperty('shop_name')])
				->setTo(App::$app->getProperty('admin_email'))
				->setBody($body, 'text/html')
			;

			// Send the message
			// отправляем письма клиенту и администратору
			$mailer->send($message_client);
			$mailer->send($message_admin);
		} catch (Exception $e) {
			
		}

		Cart::clean(); // очищаем корзину
		// выводим сообщение об успешном офрмлении заказа
		$_SESSION['success'] = 'Спасибо за Ваш заказ. В ближайшее время с Вами свяжется менеджер для согласования заказа';

		if ($pay) redirect('/payment/pay'); // если пользователь хочет оплатить заказ сразу, перенаправляем его на форму оплаты
	}

}
