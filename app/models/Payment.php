<?php
// Модель корзины

namespace app\models;

use ishop\App;

class Payment extends AppModel {

	// метод возвращает данные для оплаты заказа
	public static function getData(): array {
		return $_SESSION['payment'] ?? [];
	}

	// метод удаления данных для оплаты заказа
	public static function deleteData(): void {
		if (isset($_SESSION['payment'])) unset($_SESSION['payment']);
	}

	// метод устанавливает данные для оплаты заказа
	public static function setData(array|int $data, bool $payNow = true): array {
		// $data - данные для оплаты заказа
		// $payNow - checkbox хочет ли пользователь сразу оплатить заказ
		if ($payNow) {
			$pay['id'] = !empty($data['id']) ? $data['id'] : (int) $data; // получаем id заказа
			$pay['sum'] = !empty($_SESSION['cart']) ? $_SESSION['cart.sum'] : $data['sum']; // получаем сумму заказа
			$pay['curr'] = !empty($_SESSION['cart']) ? $_SESSION['cart.currency']['code'] : $data['curr']; // получаем валюту заказа
			$pay['ik_id'] = App::$app->getProperty('ik_id'); // получаем id кассы из реестра
			$pay['shop_name'] = App::$app->getProperty('shop_name'); // получаем название магазина из реестра
			$_SESSION['payment'] = $pay;
		}
		return $pay ?? [];
	}

}
