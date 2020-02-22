<?php
// Модель заказа

namespace app\models\admin;

use app\models\AppModel;
use ishop\libs\Pagination; // класс пагинации

class Order extends AppModel {

	public static $pagination; // пагинация
	public static $count; // число заказов
	private static $sql_part = "SELECT `order`*, `user`.`name`, ROUND(SUM(`order_product`.`price`), 2) AS `sum` FROM `order`
  JOIN `user` ON `order`.`user_id` = `user`.`id`
  JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`";
	private static $where = "";

	// получает заказы
	public static function getAll($pagination = true, $perpage = 3){
		// $perpage - число записей на 1 странице
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // номер текущей страницы
		self::$count = \R::count('order'); // число заказов
		self::$pagination = new Pagination($page, $perpage, self::$count); // пагинация
		$start = self::$pagination->getStart(); // номер записи, с которого начинать выборку
		debug("SELECT `order`.`id`, `order`.`user_id`, `order`.`status`, `order`.`date`, `order`.`update_at`, `order`.`currency`, `user`.`name`, ROUND(SUM(`order_product`.`price`), 2) AS `sum` FROM `order`
  JOIN `user` ON `order`.`user_id` = `user`.`id`
  JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
  GROUP BY `order`.`id` ORDER BY `order`.`status` DESC, `order`.`id` DESC LIMIT $start, $perpage");
		debug(self::$sql_part . self::$where . self::getSqlGroup('DESC') . " LIMIT $start, $perpage");

		// `order` - обрамляем обратными кавычками, чтобы избежать проблем с совпадением со служебными словами
		// ROUND(SUM(`order_product`.`price`), 2) - округляем сумму до 2 знаков после запятой
		// AS `sum` - задаем алиас (псевдоним) для данной записи в выборке
		// JOIN `user` - присоединяем таблицу пользователей по соответствию поля user_id в заказе и id пользователя
		// JOIN `order_product` - присоединяем товары заказа по соответствию id заказа и поля order_id в товарах заказа
		// GROUP BY `order`.`id` - группируем по id заказа
		// ORDER BY `order`.`status`, `order`.`id` - сортируем по статусу и id заказа
		// LIMIT $start, $perpage - ограничиваем выборку для вывода пагинации
		return \R::getAll("SELECT `order`.`id`, `order`.`user_id`, `order`.`status`, `order`.`date`, `order`.`update_at`, `order`.`currency`, `user`.`name`, ROUND(SUM(`order_product`.`price`), 2) AS `sum` FROM `order`
  JOIN `user` ON `order`.`user_id` = `user`.`id`
  JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
  GROUP BY `order`.`id` ORDER BY `order`.`status` DESC, `order`.`id` DESC LIMIT $start, $perpage");
		return \R::getAll(self::$sql_part . self::$where . self::getSqlGroup('DESC') . " LIMIT $start, $perpage");
	}

	// получает заказ
	public static function getById($order_id){
		// получаем данные заказа
		$order = \R::getRow("SELECT `order`.*, `user`.`name`, ROUND(SUM(`order_product`.`price`), 2) AS `sum` FROM `order`
  JOIN `user` ON `order`.`user_id` = `user`.`id`
  JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
  WHERE `order`.`id` = ?
  GROUP BY `order`.`id` ORDER BY `order`.`status`, `order`.`id` LIMIT 1", [$order_id]);
		self::$where = " WHERE `order`.`id` = ?";
		// $order2 = \R::getRow(self::$sql_part . self::$where . self::getSqlGroup('DESC') . " LIMIT 1", [$order_id]);
		debug("SELECT `order`.*, `user`.`name`, ROUND(SUM(`order_product`.`price`), 2) AS `sum` FROM `order`
  JOIN `user` ON `order`.`user_id` = `user`.`id`
  JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
  WHERE `order`.`id` = ?
  GROUP BY `order`.`id` ORDER BY `order`.`status`, `order`.`id` LIMIT 1");
		debug(self::$sql_part . self::$where . self::getSqlGroup('DESC') . " LIMIT 1");
		// если заказ не найден, выбрасываем исключение
		if(!$order){
			throw new \Exception('Страница не найдена', 404);
		}
		return $order;
	}

	private static function getSqlGroup($sort = ''){
		return " GROUP BY `order`.`id` ORDER BY `order`.`status` $sort, `order`.`id` $sort";
	}

	// удаляет заказ
	public static function delete($order_id){
		\R::trash(\R::load('order', $order_id)); // получаем данные заказа по его id и удаляем заказ
		$_SESSION['success'] = 'Заказ удален';
	}

	// экшен изменения статуса заказа
	public static function updateStatus($order_id, $status){
		$order = \R::load('order', $order_id); // получаем данные заказа по его id
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
