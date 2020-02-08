<?php

namespace app\controllers\admin;

use app\models\Category;

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

    // экшен добавления новой категории
    public function addAction(){
        // если данные из формы получены, обрабатываем их
        if(!empty($_POST)){
            $category = new Category(); // объект категории
            $data = $_POST; // данные из формы
            $category->load($data); // загружаем данные из формы в модель категории
            // валидируем данные категории
            if(!$category->validate($data)){
                $category->getErrors(); // получаем список ошибок
                redirect();
            }
            // сохраняем данные категории в таблицу БД и получаем id соханенной категории в переменную
            if($id = $category->save('category')){

            }
        }
        $this->setMeta('Новая категория');
    }

}