<?php

namespace app\controllers\admin;

class CategoryController extends AppController {

    // экшен для отображения страницы со списком категорий
    public function indexAction(){
        $this->setMeta('Список категорий');
    }

    // экшен удаления категории
    public function deleteAction(){
        $id = $this->getRequestID(); // получаем id категории
        $children = \R::count('category', 'parent_id = ?', [$id]); // считаем количество вложенных категорий
        $errors = '';
        if($children){
            $errors .= '<li>Удаление невозможно, в категории есть вложенные категории</li>';
        }
        $products = \R::count('product', 'category_id = ?', [$id]); // считаем количество товаров в данной категории
        if($products){
            $errors .= '<li>Удаление невозможно, в категории есть товары</li>';
        }
        if($errors){
            $_SESSION['error'] = "<ul>$errors</ul>";
            redirect();
        }
        $category = \R::load('category', $id); // получаем данную категорию из БД
        \R::trash($category); // удаляем категорию
        $_SESSION['success'] = 'Категория удалена';
        redirect();
    }

}