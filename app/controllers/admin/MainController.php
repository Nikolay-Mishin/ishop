<?php

namespace app\controllers\admin;

// Контроллер главной страницы админки

class MainController extends AppController {

    public function indexAction(){
        $countNewOrders = \R::count('order', "status = '0'"); // получаем новые заказы
        $countUsers = \R::count('user'); // получаем общее число зарегистрированных пользователей
        $countProducts = \R::count('product'); // получаем общее число товаров
        $countCategories = \R::count('category'); // получаем общее число категорий
        $this->setMeta('Панель управления'); // устанавливаем мета-данные
        $this->set(compact('countNewOrders', 'countCategories', 'countProducts', 'countUsers'));
    }

}