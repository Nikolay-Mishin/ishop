<?php
// контроллер карточки товара

namespace app\controllers;

class ProductController extends AppController {

    public function viewAction(){
        $alias = $this->route['alias']; // получаем алиас текущего продукта
        // получаем по алиасу информацию о текущем продукте из БД
        $product = \R::findOne('product', "alias = ? AND status = '1'", [$alias]);
        if(!$product){
            throw new \Exception('Страница не найдена', 404);
        }

        // хлебные крошки
        // строка категорий - Home / Single

        // связанные товары - с этим товаром покупают также

        // запись в куки запрошенного товара

        // просмотренные товары - последние 3 просмотренных товара

        // галерея

        // модификации

        $this->setMeta($product->title, $product->description, $product->keywords);
        $this->set(compact('product')); // передаем данные в вид карточки товара
    }

}