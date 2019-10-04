<?php
// Контроллер корзины
/** добавлять в корзину можно
 * с главной (базовый)
 * из карточки товара (базовый/модификация/количество)
 * из выбранной категории
 * из поиска
 * из фильтра (асинхронно) / добавлять товар динамично (товар, которого изначально на странице не было / делегировать событие)
 */
// за счет data-атрибута (data-id) с id продукта через JS идет добавление в корзину (без перезагрузки страницы)
// если JS отключен, то добавление идет за счет ссылки href="cart/add?id=1"

namespace app\controllers;

use app\models\Cart; // модель корзины

class CartController extends AppController {

    // добавляет товары в корзину
    public function addAction(){
        // (int) - приводим к числу
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null; // id товара
        $qty = !empty($_GET['qty']) ? (int)$_GET['qty'] : null; // количество товара
        $mod_id = !empty($_GET['mod']) ? (int)$_GET['mod'] : null; // id модификатора товара
        // если модификатор не получен - переменная будет инициализирована (значение задано), но пуста
        $mod = null; // переменная для хранения модификатора товара
        // если id получен, получаем товар
        if($id){
            $product = \R::findOne('product', 'id = ?', [$id]); // получаем товар
            // если товар не получен, возвращаем false
            if(!$product){
                return false;
            }
            // если передан id модификатора, получаем информацию по данному модификатора
            if($mod_id){
                $mod = \R::findOne('modification', 'id = ? AND product_id = ?', [$mod_id, $id]);
            }
        }
        $cart = new Cart(); // объект корзины
        $cart->addToCart($product, $qty, $mod); // вызваем метод для добавления в корзину
        // если запрос пришел асинхронно (ajax), загружаем вид корзины
        if($this->isAjax()){
            $this->loadView('cart_modal');
        }
        // перезапрашиваем страницу, если данные пришли не ajax
        redirect();
    }

    public function showAction(){
        $this->loadView('cart_modal');
    }

    public function deleteAction(){
        $id = !empty($_GET['id']) ? $_GET['id'] : null;
        if(isset($_SESSION['cart'][$id])){
            $cart = new Cart();
            $cart->deleteItem($id);
        }
        if($this->isAjax()){
            $this->loadView('cart_modal');
        }
        redirect();
    }

}