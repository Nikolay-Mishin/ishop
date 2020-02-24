<?php
// Модель заказа

namespace app\models\admin;

use app\models\AppModel;

class OrderProduct extends AppModel {

	// получает товары заказа
	public static function getByOrderId($id){
		return \R::findAll('order_product', "order_id = ?", [$id]); // получаем данные товаров заказа по его id
	}

	// удаляет товары заказа
	public static function delete($id){
		$order_products = self::getByOrder($id); // получаем данные товаров заказа по его id
		// удаляем товары заказа
		foreach($order_products as $order_product){
			\R::trash($order_product);
		}
		$_SESSION['success'] = 'Товары заказа удалены';
	}

}
