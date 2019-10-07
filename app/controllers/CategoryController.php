<?php
// Контроллер категорий

namespace app\controllers;

use app\models\Category; // модель категорий
use app\models\Breadcrumbs; // модель хлебных крошек

class CategoryController extends AppController {

    // отображает вид страницы с товарами категории
    public function viewAction(){
        $alias = $this->route['alias']; // алиас запрошенной категории
        $category = \R::findOne('category', 'alias = ?', [$alias]); // получаем категорию из БД
        // если категория не получаена выбрасываем исключение
        if(!$category){
            throw new \Exception('Страница не найдена', 404);
        }

        // хлебные крошки
        $breadcrumbs = Breadcrumbs::getBreadcrumbs($category->id); // хлебные крошки;

        $cat_model = new Category(); // модель категорий
        $ids = $cat_model->getIds($category->id); // получаем список id вложенных категорий (дочерних к запрошенной)
        // если вложенных категорий нет берем запрошенную категорию, иначе к вложенным категориям добавляем запрошенную
        $ids = !$ids ? $category->id : $ids . $category->id; // 4,6,7,5,8,9,10,1 (для категории 1)

        // товары списка полученных категория (IN ищет совпадение в заданном диапазоне - 4,6,7,5,8,9,10,1)
        // не биндим значение ("category_id IN ?", [$ids]), тк значение для выборки из БД мы формируем сами на основе данных из БД
        $products = \R::find('product', "category_id IN ($ids)");
        $this->setMeta($category->title, $category->description, $category->keywords);
        $this->set(compact('products', 'breadcrumbs'));
    }

}