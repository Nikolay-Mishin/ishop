<?php
// Контроллер категорий

namespace app\controllers;

use app\models\Category; // модель категорий
use app\models\Breadcrumbs; // модель хлебных крошек
use ishop\App;
use ishop\libs\Pagination; // класс пагинации (постраничной навигации)
use app\widgets\filter\Filter; // виджет фильтров

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

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // текущая страница
        $perpage = App::$app->getProperty('pagination'); // получаем из контейнера число записей для вывода на 1 странице
        
        // формируем sql-запрос выборки товаров по фильтрам
        $sql_part = '';
        if(!empty($_GET['filter'])){
            /*
            SELECT `product`.*  FROM `product`  WHERE category_id IN (6) AND id IN
            (
            SELECT product_id FROM attribute_product WHERE attr_id IN (1,5)
            )
            */
            $filter = Filter::getFilter(); // получаем список выбранных фильтров
            $sql_part = "AND id IN (SELECT product_id FROM attribute_product WHERE attr_id IN ($filter))";
        }

        // получаем общее число товаров из списка полученных категорий
        $total = \R::count('product', "category_id IN ($ids) $sql_part");
        $pagination = new Pagination($page, $perpage, $total); // объект пагинации
        $start = $pagination->getStart(); // получаем номер записи, с которой необходимо начинать выборку из БД

        // товары списка полученных категория (IN ищет совпадение в заданном диапазоне - 4,6,7,5,8,9,10,1)
        // не биндим значение ("category_id IN ?", [$ids]), тк значение для выборки из БД мы формируем сами на основе данных из БД
        $products = \R::find('product', "category_id IN ($ids) $sql_part LIMIT $start, $perpage");

        // если данные пришли ajax, загружаем вид фильтра и передаем соответствующие данный
        if($this->isAjax()){
            $this->loadView('filter', compact('products', 'total', 'pagination'));
        }

        $this->setMeta($category->title, $category->description, $category->keywords);
        $this->set(compact('products', 'breadcrumbs', 'pagination', 'total'));
    }

}