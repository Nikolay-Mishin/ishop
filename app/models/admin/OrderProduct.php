<?php
// Модель заказа

namespace app\models\admin;

use app\models\OrderProduct as baseOrderProduct;

class OrderProduct extends baseOrderProduct {

	// удаляет товары заказа
	public static function delete($id){
		$order_products = self::getByOrderId($id); // получаем данные товаров заказа по его id
		// удаляем товары заказа
		foreach($order_products as $order_product){
			\R::trash($order_product);
		}
		$_SESSION['success'] = 'Товары заказа удалены';
	}

}
