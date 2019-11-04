<?php

namespace app\controllers\admin;

// Контроллер главной страницы админки

class MainController extends AppController {

    public function indexAction(){
        $countNewOrders = \R::count('order', "status = '0'");
        $countUsers = \R::count('user');
        $countProducts = \R::count('product');
        $countCategories = \R::count('category');
        $this->setMeta('Панель управления'); // устанавливаем мета-данные
        $this->set(compact('countNewOrders', 'countCategories', 'countProducts', 'countUsers'));
    }

}