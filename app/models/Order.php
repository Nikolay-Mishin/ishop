<?php
// Модель заказа

namespace app\models;

use ishop\App;
use Swift_Mailer; // класс отправки писем
use Swift_Message; // класс формирования писем
use Swift_SmtpTransport; // класс smtp-сервера

class Order extends AppModel {

	public $order_id; // id заказа
	public $user_email; // email пользователя
	public $sum; // сумма заказа

	// аттрибуты модели (параметры/поля формы)
	public $attributes = [
		'user_id' => '',
		'note' => '',
		'currency' => ''
	];

	public function __construct($data){
		parent::__construct($data); // вызов родительского конструктора, чтобы его не затереть (перегрузка методов и свойств)
		$this->order_id = $this->id;
		$this->user_email = $data['user_email'];
		$this->sum = $data['sum'];
		debug($data);
		debug($_POST);
		debug($this, 1);
		$this->saveOrderProduct(); // сохраняем продукты заказа
		$this->paymentOrder(); // сохраняем продукты заказа
		$this->mailOrder(); // отправляем письмо пользователю и администратору/менеджеру
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
	public function saveOrderProduct(){
		$sql_part = ''; // переменная для хранения sql запроса
		// для каждого товара формируем sql строку значений и разделяем товары через запятую (',')
		foreach($_SESSION['cart'] as $product_id => $product){
			$product_id = (int)$product_id; // приводим id товара к числу
			// берем выражение в фигурные скобки {}, чтобы можно было в строке использовать элементы массив и свойства объекта
			$sql_part .= "({$this->order_id}, $product_id, {$product['qty']}, '{$product['title']}', {$product['price']}),";
		}
		$sql_part = rtrim($sql_part, ','); // удаляем запятую справа - в конце строки (',')
		// выполняем sql запрос
		\R::exec("INSERT INTO order_product (order_id, product_id, qty, title, price) VALUES $sql_part");
	}

	// экшен оплаты заказа
	public function paymentOrder(){
		// данные для оплаты
		if(!empty($_POST['pay'])){
			$this->setPaymentData();
			redirect(PATH . '/payment/form.php');
		}
		return false;
	}

	// метод устанавливает данные для оплаты заказа
	protected function setPaymentData(){
		if(isset($_SESSION['payment'])) unset($_SESSION['payment']);
		$_SESSION['payment']['id'] = $this->order_id;
		$_SESSION['payment']['curr'] = $_SESSION['cart.currency']['code'];
		$_SESSION['payment']['sum'] = $_SESSION['cart.sum'];
	}

	// отправляет письмо с информацией о заказе клиенту и администратору/менеджеру
	// public static function mailOrder($order_id, $user_email)
	public function mailOrder(){
		try{
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
			ob_start();
			require APP . '/views/mail/mail_order.php';
			$body = ob_get_clean();

			// письмо для клиента
			// setFrom должно совпадать с setUsername в настройках smtp
			$message_client = (new Swift_Message("Вы совершили заказ №{$this->order_id} на сайте " . App::$app->getProperty('shop_name')))
				->setFrom([App::$app->getProperty('smtp_login') => App::$app->getProperty('shop_name')]) // от кого
				->setTo($this->user_email) // кому
				->setBody($body, 'text/html') // тема
			;

			// письмо для администратора
			$message_admin = (new Swift_Message("Сделан заказ №{$this->order_id}"))
				->setFrom([App::$app->getProperty('smtp_login') => App::$app->getProperty('shop_name')])
				->setTo(App::$app->getProperty('admin_email'))
				->setBody($body, 'text/html')
			;

			// Send the message
			// отправляем письма клиенту и администратору
			$result = $mailer->send($message_client);
			$result = $mailer->send($message_admin);
		} catch (\Exception $e){
			
		}

		Cart::clean(); // очищаем корзину
		// выводим сообщение об успешном офрмлении заказа
		$_SESSION['success'] = 'Спасибо за Ваш заказ. В ближайшее время с Вами свяжется менеджер для согласования заказа';
	}

}
