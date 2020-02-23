<?php

namespace app\controllers\admin;

use app\models\admin\Category;
use app\models\admin\Currency;
use app\models\admin\FilterAttr;
use app\models\admin\FilterGroup;
use app\models\admin\Order;
use app\models\admin\Product;
use app\models\admin\User;

// Контроллер главной страницы админки

class MainController extends AppController {

    public function indexAction(){
        $countNewOrders = Order::getCountNew(); // получаем новые заказы
        $countUsers = User::getCount(); // получаем общее число зарегистрированных пользователей
        $countProducts = Product::getCount(); // получаем общее число товаров
        $countCategories = Category::getCount(); // получаем общее число категорий
        $countGroups = FilterGroup::getCount(); // получаем общее число групп фильтров
        $countAttributes = FilterAttr::getCount(); // получаем общее число аттрибутов фильтров
        $countCurrencies = Currency::getCount(); // получаем общее число валют
        $this->setMeta('Панель управления'); // устанавливаем мета-данные
        $this->set(compact('countNewOrders', 'countCategories', 'countProducts', 'countUsers', 'countGroups', 'countAttributes', 'countCurrencies'));
    }

}
