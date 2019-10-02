<?php

namespace app\controllers;

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

        // просмотренные товары - последние 3 просмотренных товара

        // галерея

        // модификации

        $this->setMeta($product->title, $product->description, $product->keywords);
        $this->set(compact('product', 'related')); // передаем данные в вид карточки товара
    }

}