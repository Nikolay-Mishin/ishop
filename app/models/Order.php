<?php
// Модель заказа

namespace app\models;

class Order extends AppModel {

    public $order_id; // id заказа
    public $user_email; // email пользователя
    // аттрибуты модели (параметры/поля формы)
    public $attributes = [
        'user_id' => '',
        'note' => '',
        'currency' => ''
    ];

    public function __construct($data){
        parent::__construct($data); // вызов родительского конструктора, чтобы его не затереть (перегрузка методов и свойств)
        $this->order_id = $this->last_insert_id;
        $this->user_email = $data['user_email'];
        $this->saveOrderProduct(); // сохраняем продукты заказа
        $this->mailOrder($this->order_id, $this->user_email); // отправляем письмо пользователю и администратору/менеджеру
    }

    // сохраняет оформленный заказ
    // ДЗ - сделать правильное сохранение заказа (с помощью метода save в базовой модели)
    /* public static function saveOrder($data){
        $order = \R::dispense('order'); // создаем запись для сохранения данных в БД
        $order->user_id = $data['user_id']; // id пользователя
        $order->note = $data['note']; // примечание к заказу
        $order->currency = $_SESSION['cart.currency']['code']; // валюта заказа
        $order_id = \R::store($order); // сохраняем данные заказа в БД
        self::saveOrderProduct($order_id); // сохраняет продукты данного заказа
        return $order_id;
    } */

    // сохраняет продукты данного заказа
    // public static function saveOrderProduct($order_id)
    public function saveOrderProduct(){
        $sql_part = ''; // переменная для хранения sql запроса
        // для каждого товара формируем sql строку значений и разделяем товары через запятую (',')
        foreach($_SESSION['cart'] as $product_id => $product){
            $product_id = (int)$product_id; // приводим id товара к числу
            // берем выражение в фигурные скобки {}, чтобы можно было в строке использовать элементы массив и свойства объекта
            $sql_part .= "({$this->order_id}, $product_id, {$product['qty']}, '{$product['title']}', {$product['price']}),";
        }
        $sql_part = rtrim($sql_part, ','); // удаляем запятую справа - в конце строки (',')
        // выполняем sql запрос
        \R::exec("INSERT INTO order_product (order_id, product_id, qty, title, price) VALUES $sql_part");
    }

    // отправляет письмо с информацией о заказе клиенту и администратору/менеджеру
    // public static function mailOrder($order_id, $user_email)
    public function mailOrder($order_id, $user_email){
        // Create the Transport
        $transport = (new Swift_SmtpTransport(App::$app->getProperty('smtp_host'), App::$app->getProperty('smtp_port'), App::$app->getProperty('smtp_protocol')))
            ->setUsername(App::$app->getProperty('smtp_login'))
            ->setPassword(App::$app->getProperty('smtp_password'))
        ;
        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

        // Create a message
        ob_start();
        require APP . '/views/mail/mail_order.php';
        $body = ob_get_clean();

        $message_client = (new Swift_Message("Вы совершили заказ №{$order_id} на сайте " . App::$app->getProperty('shop_name')))
            ->setFrom([App::$app->getProperty('smtp_login') => App::$app->getProperty('shop_name')])
            ->setTo($user_email)
            ->setBody($body, 'text/html')
        ;

        $message_admin = (new Swift_Message("Сделан заказ №{$order_id}"))
            ->setFrom([App::$app->getProperty('smtp_login') => App::$app->getProperty('shop_name')])
            ->setTo(App::$app->getProperty('admin_email'))
            ->setBody($body, 'text/html')
        ;

        // Send the message
        $result = $mailer->send($message_client);
        $result = $mailer->send($message_admin);
        unset($_SESSION['cart']);
        unset($_SESSION['cart.qty']);
        unset($_SESSION['cart.sum']);
        unset($_SESSION['cart.currency']);
        $_SESSION['success'] = 'Спасибо за Ваш заказ. В ближайшее время с Вами свяжется менеджер для согласования заказа';
    }

}