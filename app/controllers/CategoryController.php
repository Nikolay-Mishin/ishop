<?php
// Контроллер категорий

namespace app\controllers;

use app\models\Category; // модель категорий
use app\models\Breadcrumbs; // модель хлебных крошек
use ishop\App;
use ishop\libs\Pagination; // класс пагинации (постраничной навигации)

class CategoryController extends AppController {

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

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perpage = App::$app->getProperty('pagination');
        $total = \R::count('product', "category_id IN ($ids)");
        $pagination = new Pagination($page, $perpage, $total);
        $start = $pagination->getStart();

        // товары списка полученных категория (IN ищет совпадение в заданном диапазоне - 4,6,7,5,8,9,10,1)
        // не биндим значение ("category_id IN ?", [$ids]), тк значение для выборки из БД мы формируем сами на основе данных из БД
        $products = \R::find('product', "category_id IN ($ids) LIMIT $start, $perpage");
        $this->setMeta($category->title, $category->description, $category->keywords);
        $this->set(compact('products', 'breadcrumbs', 'pagination', 'total'));
    }

}