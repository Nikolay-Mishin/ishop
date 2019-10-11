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
use app\models\Order; // модель заказов
use app\models\User; // модель пользователя

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
            // если передан id модификатора, получаем информацию по данному модификатору
            if($mod_id){
                $mod = \R::findOne('modification', 'id = ? AND product_id = ?', [$mod_id, $id]);
            }
        }
        $cart = new Cart(); // объект корзины
        $cart->addToCart($product, $qty, $mod); // вызываем метод для добавления в корзину
        $this->show(); // отображаем вид корзины
    }

    // отображает вид корзины
    public function showAction(){
        $this->loadView('cart_modal'); // метод фреймворка (Controller) для загрузки отдельного вида
    }

    // удаляет товар из корзины
    public function deleteAction(){
        $id = !empty($_GET['id']) ? $_GET['id'] : null; // id удаляемого товара
        if(isset($_SESSION['cart'][$id])){
            $cart = new Cart(); // объект корзины
            $cart->deleteItem($id); // удаляем данный элемент из корзины
        }
        $this->show(); // отображаем вид корзины
    }

    // очищает корзину
    public function clearAction(){
        // удаляем элементы корзины из сессии
        unset($_SESSION['cart']);
        unset($_SESSION['cart.qty']);
        unset($_SESSION['cart.sum']);
        unset($_SESSION['cart.currency']);
        $this->showAction(); // отображаем вид корзины
    }

    // отображает вид корзины при переходе к оформлению заказа
    public function viewAction(){
        $breadcrumbs = Breadcrumbs::getBreadcrumbs('Корзина'); // хлебные крошки
        $this->setMeta('Корзина');
        $this->set(compact('breadcrumbs'));
    }

    // обрабатывает данные формы офрмления заказа
    public function checkoutAction(){
        if(!empty($_POST)){
            // регистрация пользователя
            if(!User::checkAuth()){
                $user = new User(); // объект пользователя
                $data = $_POST; // массив полученных данных
                $user->load($data); // загружаем полученные данные в модель
                // если валидация и проверка на уникальность не пройдена
                if(!$user->validate($data) || !$user->checkUnique()){
                    $user->getErrors(); // получаем ошибоки
                    $_SESSION['form_data'] = $data; // запоминаем данные формы
                    redirect(); // перезапрашиваем страницу
                }else{
                    // хэшируем пароль
                    $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
                    // сохраняем пользователя и получаем id нового пользователя
                    if(!$user_id = $user->save('user')){
                        $_SESSION['error'] = 'Ошибка!';
                        redirect(); // перезапрашиваем страницу
                    }
                }
            }

            // сохранение заказа
            // сохраняем id пользователя (только что зарегистрированного или авторизованного)
            $data['user_id'] = isset($user_id) ? $user_id : $_SESSION['user']['id'];
            $data['note'] = !empty($_POST['note']) ? $_POST['note'] : ''; // примечание к заказу
            // email пользователя получаем из сессии (для авторизованного) или из данных формы регистрации
            $user_email = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : $_POST['email'];
            $order_id = Order::saveOrder($data); // сохраняем заказ и получаем его id
            Order::mailOrder($order_id, $user_email); // отправляем письмо с информацией о заказе клиенту и администратору/менеджеру
        }
        redirect(); // перезапрашиваем страницу
    }

    // отображает вид корзины, если запрос пришел через ajax, или перенаправляет пользователя на предыдущую страницу
    private function show(){
        // если запрос пришел асинхронно (ajax), загружаем вид корзины
        if($this->isAjax()){
            $this->showAction();
        }
        redirect(); // перезапрашиваем страницу, если данные пришли не ajax
    }

}