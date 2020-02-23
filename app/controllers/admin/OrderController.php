<?php

namespace app\controllers\admin;

use app\models\admin\Order; // модель заказа
use app\models\admin\OrderProduct; // модель товаров заказа

class OrderController extends AppController {

    // экшен просмотра страницы с заказами
    public function indexAction(){
        list($orders, $pagination, $count) = [Order::getAll(), Order::$pagination, Order::$count];
        $this->setMeta('Список заказов');
        $this->set(compact('orders', 'pagination', 'count'));
    }

    // экшен для отображения вида отдельного заказа
    public function viewAction(){
        $order_id = $this->getRequestID(); // получаем id заказа
        list($order, $order_products) = [Order::getById($order_id), OrderProduct::getByOrderId($order_id)];
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
