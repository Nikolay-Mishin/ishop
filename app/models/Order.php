<?php
// Модель заказа

namespace app\models;

class Order extends AppModel {

    // аттрибуты модели (параметры/поля формы)
    public $attributes = [
        'user_id' => '',
        'note' => '',
        'currency' => ''
    ];

    // сохраняет оформленный заказ
    // ДЗ - сделать правильное сохранение заказа (с помощью метода save в базовой модели)
    public static function saveOrder($data){
        $order = \R::dispense('order'); // создаем запись для сохранения данных в БД
        $order->user_id = $data['user_id']; // id пользователя
        $order->note = $data['note']; // примечание к заказу
        $order->currency = $_SESSION['cart.currency']['code']; // валюта заказа
        $order_id = \R::store($order); // сохраняем данные заказа в БД
        self::saveOrderProduct($order_id); // сохраняет продукты данного заказа
        return $order_id;
    }

    // сохраняет продукты данного заказа
    public static function saveOrderProduct($order_id){
        $sql_part = '';
        foreach($_SESSION['cart'] as $product_id => $product){
            $product_id = (int)$product_id;
            $sql_part .= "($order_id, $product_id, {$product['qty']}, '{$product['title']}', {$product['price']}),";
        }
        $sql_part = rtrim($sql_part, ',');
        \R::exec("INSERT INTO order_product (order_id, product_id, qty, title, price) VALUES $sql_part");
    }

    // отправляет письмо с информацией о заказе клиенту и администратору/менеджеру
    public static function mailOrder($order_id, $user_email){

    }

}