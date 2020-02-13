<?php

namespace app\controllers\admin;

use ishop\libs\Pagination;

class ProductController extends AppController {

    public function indexAction(){
        /*
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // текущая страница пагинации
        $perpage = 10; // число записей на 1 странице
        $count = \R::count('product'); // число продуктов
        $pagination = new Pagination($page, $perpage, $count, 'product'); // объект пагинации
        $start = $pagination->getStart(); // иницилизируем объект пагинации
        // получаем список продуктов для текущей страницы пагинации
        $products = \R::getAll("SELECT product.*, category.title AS cat FROM product JOIN category ON category.id = product.category_id ORDER BY product.title LIMIT $start, $perpage");
        */
        $pagination = new Pagination(null, 10, null, 'product'); // объект пагинации
        // получаем список продуктов для текущей страницы пагинации
        $products = \R::getAll("SELECT product.*, category.title AS cat FROM product JOIN category ON category.id = product.category_id ORDER BY product.title $pagination->limit");
        $this->setMeta('Список товаров'); // устанавливаем мета-данные
        $this->set(compact('products', 'pagination')); // передаем данные в вид
    }

}