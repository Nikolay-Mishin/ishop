<?php
// Контроллер поиска

namespace app\controllers;

class SearchController extends AppController{

    // обрабатывает поисковый ajax-запрос
    public function typeaheadAction(){
        if($this->isAjax()){
            // trim - обрезает пробелы с обоих сторон
            $query = !empty(trim($_GET['query'])) ? trim($_GET['query']) : null; // пришедший поисковый запрос
            // если поисковый запрос не пуст, получаем товары соответствующие наименованию в запросе
            if($query){
                // LIKE - сравниваемое выражение похоже на 'title'
                // %{$query}% - слева и справа от выражения в запросе могут быть любые символы
                // возвращаем данные на 1 больше, чем мы хотим увидеть (10)
                $products = \R::getAll('SELECT id, title FROM product WHERE title LIKE ? LIMIT 11', ["%{$query}%"]);
                echo json_encode($products); // выводим полученные товары в виде json (кодируем массив в json)
            }
        }
        die;
    }

}