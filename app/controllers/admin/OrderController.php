<?php

namespace app\controllers\admin;

use app\models\admin\Order; // модель заказа
use app\models\admin\OrderProduct; // модель товаров заказа

class OrderController extends AppController {

    // экшен просмотра страницы с заказами
    public function indexAction(){
        $orders = Order::getAll(); // получаем заказы
        $pagination = Order::$pagination; // пагинация
        $count = Order::$count; // число заказов
        $this->setMeta('Список заказов');
        $this->set(compact('orders', 'pagination', 'count'));
    }

    // экшен для отображения вида отдельного заказа
    public function viewAction(){
        $order_id = $this->getRequestID(); // получаем id заказа
        $order = Order::getById($order_id); // получаем данные заказа
        $order_products = OrderProduct::getByOrder($order_id); // получаем данные товаров заказа по его id
        $this->setMeta("Заказ №{$order_id}");
        $this->set(compact('order', 'order_products'));
    }

    // экшен удаления заказа
    public function deleteAction(){
        $order_id = $this->getRequestID(); // получаем id заказа
        Order::delete($order_id); // удаляем заказ
        OrderProduct::delete($order_id); // удаляем товары заказа
        redirect(ADMIN . '/order'); // перенаправляем на страницу списка заказов
    }

    // экшен изменения статуса заказа
    public function changeAction(){
        // если передан статус и он не равен 0 (false), присваием ему 1
        Order::updateStatus($this->getRequestID(), !empty($_GET['status']) ? '1' : '0');
        redirect(); // перенаправляем на предыдущую страницу
    }

}
