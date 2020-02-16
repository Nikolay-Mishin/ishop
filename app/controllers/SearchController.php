<?php
// Контроллер поиска

namespace app\controllers;

use app\models\Breadcrumbs; // модель хлебных крошек

class SearchController extends AppController{

    // обрабатывает поисковый ajax-запрос
    public function typeaheadAction(){
        if($this->isAjax()){
            // trim - обрезает пробелы с обоих сторон
            $query = !empty(trim($_GET['query'])) ? trim($_GET['query']) : null; // пришедший поисковый запрос
            // если поисковый запрос не пуст, получаем товары соответствующие строке запроса
            if($query){
                // LIKE - сравниваемое выражение похоже на 'title'
                // %{$query}% - слева и справа от выражения в запросе могут быть любые символы
                // возвращаем данные на 1 больше, чем мы хотим увидеть (10)
                $products = \R::getAll("SELECT id, title FROM product WHERE title LIKE ? AND status = '1' LIMIT 11", ["%{$query}%"]);
                echo json_encode($products); // выводим полученные товары в виде json (кодируем массив в json)
            }
        }
        die;
    }

    // страница с результатими поиска
    public function indexAction(){
        $query = !empty(trim($_GET['s'])) ? trim($_GET['s']) : null; // пришедший поисковый запрос
        // если поисковый запрос не пуст, получаем товары соответствующие строке запроса
        if($query){
            // SELECT `product`.*  FROM `product`  WHERE title LIKE ?
            $products = \R::find('product', "title LIKE ? AND status = '1'", ["%{$query}%"]);
        }
        $breadcrumbs = Breadcrumbs::getBreadcrumbs(null, 'Поиск по запросу: "' . h($query) . '"'); // хлебные крошки
        $this->setMeta('Поиск по: ' . h($query)); // устанавливаем мета-данные и обрабатываем их от XSS-атак (html-инъекций)
        $this->set(compact('products', 'query', 'breadcrumbs'));
    }

}
