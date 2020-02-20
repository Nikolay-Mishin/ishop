<?php

namespace app\controllers\admin;


use ishop\libs\Pagination;

class OrderController extends AppController {

    // экшен просмотра страницы с заказами
    public function indexAction(){
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // номер текущей страницы
        $perpage = 3; // число записей на 1 странице
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
  GROUP BY `order`.`id` ORDER BY `order`.`status` DESC, `order`.`id` LIMIT $start, $perpage");

        $this->setMeta('Список заказов');
        $this->set(compact('orders', 'pagination', 'count'));
    }

    // экшен для отображения вида отдельного заказа
    public function viewAction(){
        $order_id = $this->getRequestID(); // получаем id заказа
        // получаем данные заказа
        $order = \R::getRow("SELECT `order`.*, `user`.`name`, ROUND(SUM(`order_product`.`price`), 2) AS `sum` FROM `order`
  JOIN `user` ON `order`.`user_id` = `user`.`id`
  JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
  WHERE `order`.`id` = ?
  GROUP BY `order`.`id` ORDER BY `order`.`status`, `order`.`id` LIMIT 1", [$order_id]);
        // если заказ не найден, выбрасываем исключение
        if(!$order){
            throw new \Exception('Страница не найдена', 404);
        }
        $order_products = \R::findAll('order_product', "order_id = ?", [$order_id]);
        $this->setMeta("Заказ №{$order_id}");
        $this->set(compact('order', 'order_products'));
    }

    // экшен удаления заказа
    public function deleteAction(){
        $order_id = $this->getRequestID(); // получаем id заказа
        $order = \R::load('order', $order_id); // получаем данные заказа по его id
        \R::trash($order); // удаляем заказ
        $_SESSION['success'] = 'Заказ удален';
        redirect(ADMIN . '/order'); // перенаправляем на страницу списка заказов
    }

    // экшен изменения статуса заказа
    public function changeAction(){
        $order_id = $this->getRequestID(); // получаем id заказа
        $status = !empty($_GET['status']) ? '1' : '0'; // если передан статус и он не равен 0 (false), присваием ему 1
        $order = \R::load('order', $order_id); // получаем данные заказа по его id
        // если заказ не найден, выбрасываем исключение
        if(!$order){
            throw new \Exception('Страница не найдена', 404);
        }
        $order->status = $status; // записываем статус заказа
        $order->update_at = date("Y-m-d H:i:s"); // записываем дату изменения заказа
        \R::store($order); // сохраняем изменения в БД
        $_SESSION['success'] = 'Изменения сохранены';
        redirect(); // перенаправляем на предыдущую страницу
    }

}
