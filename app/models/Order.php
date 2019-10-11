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

    }

    // отправляет письмо с информацией о заказе клиенту и администратору/менеджеру
    public static function mailOrder($order_id, $user_email){

    }

}