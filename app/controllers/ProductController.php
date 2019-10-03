<?php
// Контроллер продукта - карточка товара
/**
 * информация о текущем продукте
 * хлебные крошки
 * связанные товары
 * запись в куки запрошенного товара
 * просмотренные товары
 * галерея
 * модификации */

namespace app\controllers;

use app\models\Product;

class ProductController extends AppController {

    public function viewAction(){
        $alias = $this->route['alias']; // получаем алиас текущего продукта
        // получаем по алиасу информацию о текущем продукте из БД
        $product = \R::findOne('product', "alias = ? AND status = '1'", [$alias]);
        // если продукт не найден выбрасываем исключение
        if(!$product){
            throw new \Exception('Страница не найдена', 404);
        }

        // хлебные крошки - Home / Single

        // связанные товары - с этим товаром покупают также
        $related = \R::getAll("SELECT * FROM related_product JOIN product ON product.id = related_product.related_id WHERE related_product.product_id = ?", [$product->id]);

        // запись в куки запрошенного товара
        $p_model = new Product(); // объект модели продукта
        $p_model->setRecentlyViewed($product->id); // записываем запрошенный товар в список просмотренных

        // просмотренные товары - последние 3 просмотренных товара
        // ДЗ - сделать ссылку посмотреть все (product/recentlyViewed - ProductController => recentlyViewed())
        $r_viewed = $p_model->getRecentlyViewed(); // получаем просмотренные товары
        $recentlyViewed = null; // переменная для хранения просмотренных товаров, полученных из БД
        // если просмотренные товары получены из кук, получаем их из БД
        if($r_viewed){
            // find запрос типа IN - ищет совпадение в таблице (по заданному полю) из переданных значений (массива)
            // \R::genSlots - формирует подстановку из '?' по числу элементов массива (?,?,?)
            // SQL - SELECT column-names FROM table-name WHERE column-name IN (values) 
            $recentlyViewed = \R::find('product', 'id IN (' . \R::genSlots($r_viewed) . ') LIMIT 3', $r_viewed);
        }

        // галерея
        $gallery = \R::findAll('gallery', 'product_id = ?', [$product->id]);

        // модификации

        $this->setMeta($product->title, $product->description, $product->keywords);
        // передаем данные в вид карточки товара
        $this->set(compact('product', 'related', 'gallery', 'recentlyViewed'));
    }

}