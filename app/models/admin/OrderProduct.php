<?php
// Модель заказа

namespace app\models\admin;

use app\models\AppModel;

class OrderProduct extends AppModel {

	// получает товары заказа
	public static function getByOrder($order_id){
		return \R::findAll('order_product', "order_id = ?", [$order_id]); // получаем данные товаров заказа по его id
	}

	// удаляет товары заказа
	public static function delete($order_id){
		$order_products = self::getByOrder($order_id); // получаем данные товаров заказа по его id
		// удаляем товары заказа
		foreach($order_products as $order_product){
			\R::trash($order_product);
		}
		$_SESSION['success'] = 'Товары заказа удалены';
	}

}
