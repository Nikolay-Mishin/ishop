<?php
// Модель заказа

namespace app\models\admin;

use app\models\AppModel;
use ishop\libs\Pagination; // класс пагинации

class Order extends AppModel {

	public static $pagination; // пагинация
	protected static $select = '`order`.*, ROUND(SUM(`order_product`.`price`), 2) AS `sum`';
	protected static $join = '`user` ON `order`.`user_id` = `user`.`id`, `order_product` ON `order`.`id` = `order_product`.`order_id`';
	protected static $where = '';
	protected static $group = '`order`.`id`';
	protected static $sort = '';
	protected static $order = '`order`.`status`, `order`.`id`';
	protected static $limit = '1';

	// получает общее число заказов
	//public static function getCount(){
	//    return \R::count('order');
	//}

	// получает число новых заказов
	public static function getCountNew(){
		return self::getCount("status = '0'");
	}

	// получает заказы
	public static function getAll($pagination = true, $perpage = 3){
		// `order`* => `order`.`id`, `order`.`user_id`, `order`.`status`, `order`.`date`, `order`.`update_at`, `order`.`currency`
		/* "SELECT `order`.*, `user`.`name`, ROUND(SUM(`order_product`.`price`), 2) AS `sum` FROM `order`
  JOIN `user` ON `order`.`user_id` = `user`.`id`
  JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
  GROUP BY `order`.`id` ORDER BY `order`.`status` DESC, `order`.`id` DESC LIMIT $start, $perpage"; */
		// self::$sort = 'DESC';
		// self::$limit = "$start, $perpage";

		// `order` - обрамляем обратными кавычками, чтобы избежать проблем с совпадением со служебными словами
		// ROUND(SUM(`order_product`.`price`), 2) - округляем сумму до 2 знаков после запятой
		// AS `sum` - задаем алиас (псевдоним) для данной записи в выборке
		// JOIN `user` - присоединяем таблицу пользователей по соответствию поля user_id в заказе и id пользователя
		// JOIN `order_product` - присоединяем товары заказа по соответствию id заказа и поля order_id в товарах заказа
		// GROUP BY `order`.`id` - группируем по id заказа
		// ORDER BY `order`.`status`, `order`.`id` - сортируем по статусу и id заказа
		// LIMIT $start, $perpage - ограничиваем выборку для вывода пагинации
		self::$pagination = new Pagination(null, $perpage, null, 'order'); // объект пагинации
		list(self::$select, self::$sort, self::$limit) = [self::$select . ', `user`.`name`', 'DESC', self::$pagination->limit];
		$orders = \R::getAll(self::getSql());
		debug(self::$sql);

		// "UPDATE currency SET 'sql_part', update_at = NOW() WHERE code = ?"
		// debug(self::$update);

		// "INSERT INTO '$table' ('condition', 'attr_id') VALUES ('sql_part', 'sql_part')"
		// debug(self::$insert);

		// "DELETE FROM 'table' WHERE 'condition' = ?"
		// debug(self::$delete);

		return $orders;
		return \R::getAll(self::getSql());
	}

	// получает заказ
	public static function getById($id){
		// `order`* => `order`.`id`, `order`.`user_id`, `order`.`status`, `order`.`date`, `order`.`update_at`, `order`.`currency`
		/* "SELECT `order`.*, ROUND(SUM(`order_product`.`price`), 2) AS `sum`, `user`.`name` FROM `order`
  JOIN `user` ON `order`.`user_id` = `user`.`id`
  JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
  WHERE `order`.`id` = ?
  GROUP BY `order`.`id` ORDER BY `order`.`status`, `order`.`id` LIMIT 1"; */
		list(self::$select, self::$where) = [self::$select . ', `user`.`name`', '`order`.`id` = ?'];
		$order = \R::getRow(self::getSql(), [$id]); // получаем данные заказа
		debug(self::$sql);
		// если заказ не найден, выбрасываем исключение
		if(!$order){
			throw new \Exception('Страница не найдена', 404);
		}
		return $order;
	}

	// получает заказы пользователя
	public static function getByUserId($id, $limit = ''){
		// `order`.* => `order`.`id`, `order`.`user_id`, `order`.`status`, `order`.`date`, `order`.`update_at`, `order`.`currency`
		/*
		"SELECT `order`.*, ROUND(SUM(`order_product`.`price`), 2) AS `sum` FROM `order`
  JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
  WHERE user_id = {$id} GROUP BY `order`.`id` ORDER BY `order`.`status` DESC, `order`.`id` DESC LIMIT $start, $perpage"
		*/
		$values = ['`order_product` ON `order`.`id` = `order_product`.`order_id`', 'user_id = ?', 'DESC', $limit ? $limit : self::$limit];
		list(self::$join, self::$where, self::$sort, self::$limit) = $values;
		$orders = \R::getAll(self::getSql(), [$id]);
		debug(self::$sql);
		return $orders;
		return \R::getAll(self::getSql(), [$id]);
	}

	// удаляет заказ
	public static function delete($id){
		\R::trash(\R::load('order', $id)); // получаем данные заказа по его id и удаляем заказ
		$_SESSION['success'] = 'Заказ удален';
	}

	// экшен изменения статуса заказа
	public static function updateStatus($id, $status){
		$order = \R::load('order', $id); // получаем данные заказа по его id
		// если заказ не найден, выбрасываем исключение
		if(!$order){
			throw new \Exception('Страница не найдена', 404);
		}
		$order->status = $status; // записываем статус заказа
		$order->update_at = date("Y-m-d H:i:s"); // записываем дату изменения заказа
		\R::store($order); // сохраняем изменения в БД
		$_SESSION['success'] = 'Изменения сохранены';
	}

}
