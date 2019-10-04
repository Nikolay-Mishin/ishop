<?php
// Можель корзины

namespace app\models;

// пример вида корзины (будет храница в сессии - $_SESSION['cart'])
// базовый вариант (без модификаторов)
/*Array
(
    [1] => Array // товар 1 без модификатора
        (
            [qty] => QTY // количество
            [name] => NAME
            [alias] => ALIAS
            [price] => PRICE
            [img] => IMG
        )
    [10] => Array
        (
            [qty] => QTY
            [name] => NAME
            [alias] => ALIAS
            [price] => PRICE
            [img] => IMG
        )
    )
    [1-2] => Array // товар 1 с модификатором 2
        (
            [qty] => QTY // количество
            [name] => NAME
            [alias] => ALIAS
            [price] => PRICE
            [img] => IMG
        )
    [qty] => QTY, // общее количество
    [sum] => SUM // конечная цена в выбранной пользователем валюте (сумма qty * price)
*/

use ishop\App;

class Cart extends AppModel {

    // добавляет товар в корзину
    public function addToCart($product, $qty = 1, $mod = null){
        // если в сессии нет элемента 'cart.currency', то создадим его
        if(!isset($_SESSION['cart.currency'])){
            $_SESSION['cart.currency'] = App::$app->getProperty('currency'); // получаем актиыную валюту из контейнера
        }
        // если модификатор получен, кладем в корзину его, иначе базовый товар
        if($mod){
            $ID = "{$product->id}-{$mod->id}"; // ID товара с модификатором
            $title = "{$product->title} ({$mod->title})"; // наименование товара с модификатором
            $price = $mod->price; // цена товара с модификатором
        }else{
            $ID = $product->id; // ID базового товара
            $title = $product->title; // наименование базового товара
            $price = $product->price; // цена базового товара
        }
        // если товар уже есть в сессии, то добавляем его к уже существующему, иначе добавляем товар в сессию
        if(isset($_SESSION['cart'][$ID])){
            $_SESSION['cart'][$ID]['qty'] += $qty; // прибавляем количество переданного товара к уже существующему
        }else{
            // создаем товар с заданным ID в сессии
            $_SESSION['cart'][$ID] = [
                'qty' => $qty,
                'title' => $title,
                'alias' => $product->alias,
                'price' => $price * $_SESSION['cart.currency']['value'], // умножаем цену на активную валюту
                'img' => $product->img,
            ];
        }
        // переданное количество товара прибавляем к уже имеющемуся, иначе записываем переданное количество
        $_SESSION['cart.qty'] = isset($_SESSION['cart.qty']) ? $_SESSION['cart.qty'] + $qty : $qty;
        // количесство, умноженное на цену в активной валюте, прибавляем к уже имеющемуся, 
        // иначе записываем количесство, умноженное на цену в активной валюте
        $_SESSION['cart.sum'] = isset($_SESSION['cart.sum']) ? $_SESSION['cart.sum'] + $qty * ($price * $_SESSION['cart.currency']['value']) : $qty * ($price * $_SESSION['cart.currency']['value']);
    }

}