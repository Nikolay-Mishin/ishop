<?php

namespace app\controllers\admin;

use ishop\libs\Pagination;

class OrderController extends AppController {

    public function indexAction(){
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // номер текущей страницы
        $perpage = 1; // число записей на 1 странице
        $count = \R::count('order'); // число заказов
        $pagination = new Pagination($page, $perpage, $count); // пагинация
        $start = $pagination->getStart(); // номер записи, с которого начинать выборку

        // получаем заказы
        // `order` - обрамляем обратными кавычками, чтобы избежать проблем с совпадением со служебными словами
        // ROUND(SUM(`order_product`.`price`), 2) - округляем сумму до 2 знаков после запятой
        // AS `sum` - задаем алиас (псевдоним) для данной записи в выборке
        // JOIN `user` - присоединяем таблицу пользователей по соответствию поля user_id в заказе и id пользователя
        // JOIN `order_product` - присоединяем товары заказа по соответствию id заказа и поля order_id в товарах заказа
        // GROUP BY `order`.`id` - группируем по id заказа
        // ORDER BY `order`.`status`, `order`.`id` - сортируем по статусу и id заказа
        // LIMIT $start, $perpage - ограничиваем выборку для вывода пагинации
        $orders = \R::getAll("SELECT `order`.`id`, `order`.`user_id`, `order`.`status`, `order`.`date`, `order`.`update_at`, `order`.`currency`, `user`.`name`, ROUND(SUM(`order_product`.`price`), 2) AS `sum` FROM `order`
        JOIN `user` ON `order`.`user_id` = `user`.`id`
        JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
        GROUP BY `order`.`id` ORDER BY `order`.`status`, `order`.`id` LIMIT $start, $perpage");

        $this->setMeta('Список заказов');
        $this->set(compact('orders', 'pagination', 'count'));
    }

}