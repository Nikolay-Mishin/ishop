<?php
// Модель заказа

namespace app\models;

class Order extends AppModel {

    // сохраняет оформленный заказ
    public static function saveOrder($data){

    }

    // сохраняет продукты данного заказа
    public static function saveOrderProduct($order_id){

    }

    // отправляет письмо с информацией о заказе клиенту и администратору/менеджеру
    public static function mailOrder($order_id, $user_email){

    }

}