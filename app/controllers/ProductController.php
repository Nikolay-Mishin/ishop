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
        $p_model = new Product();
        $p_model->setRecentlyViewed($product->id);

        // просмотренные товары - последние 3 просмотренных товара
        $r_viewed = $p_model->getRecentlyViewed();
        $recentlyViewed = null;
        if($r_viewed){
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