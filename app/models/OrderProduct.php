<?php
// Модель заказа

namespace app\models;

use app\models\AppModel;

class OrderProduct extends AppModel {

	// получает товары заказа
	public static function getByOrderId($id){
		return \R::findAll('order_product', "order_id = ?", [$id]); // получаем данные товаров заказа по его id
	}

}
