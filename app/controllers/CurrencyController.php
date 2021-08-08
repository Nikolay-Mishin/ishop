<?php
// контроллер валют - смена активной валюты и пересчет корзины в новую валюту

namespace app\controllers;

use app\models\Cart; // модель корзины

class CurrencyController extends AppController {

    // метод выбора валют
    public function changeAction(): void {
        // если передан get-параметр 'curr', записываем его значение, иначе null
        $currency = !empty($_GET['curr']) ? $_GET['curr'] : null;
        // если значение валюты передано (не null), то выбираем по коду валюту из БД и записываем ее в куки
        if ($currency) {
            // для защиты от sql инъекций 'code = ?' ? заменяется на значение [$currency]
            // ДЗ - делать не запрос к БД, а выбирать валюту из контейнера реестра
            $curr = \R::findOne('currency', 'code = ?', [$currency]);
            // записываем валюту в куки на 1 неделю для всего домена ('/') и пересчитываем корзину в новую валюту
            if (!empty($curr)) {
                setcookie('currency', $currency, time() + 3600*24*7, '/');
                Cart::recalc($curr); // вызываем метод пересчита корзины
            }
        }
        redirect(); // перезапрашиваем текущую страницу
    }

}
