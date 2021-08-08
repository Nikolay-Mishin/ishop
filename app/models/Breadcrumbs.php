<?php
// Модель хлебных крошек - строка с ссылками на главную и категории (Home / Single)

namespace app\models;

use ishop\App;

class Breadcrumbs {

    // получаем хлебные крошки - строит хлебные крошки
    public static function getBreadcrumbs(?int $category_id = null, string $name = ''): string {
        // $name - наименование товара
        $cats = App::$app->getProperty('cats'); // получаем категории
        $breadcrumbs_array = self::getParts($cats, $category_id); // формируем массив хлебных крошек
        // формируем html-разметку для хлебных крошек
        $breadcrumbs = "<li><a href='" . PATH . "'>Главная</a></li>"; // ссылка на главную
        // если массив хлебных крошек не пуст, формируем ссылки на категории (для элементов массива)
        if ($breadcrumbs_array) {
            foreach ($breadcrumbs_array as $alias => $title) {
                $link = "<a href='" . PATH . "/category/{$alias}'>{$title}</a>"; // ссылка на категорию
                // если не передано наименование товара последнюю категорюю выводим текст
                $link = $title == end($breadcrumbs_array) && !$name ? $title : $link;
                $class = $title == end($breadcrumbs_array) && !$name ? 'class="active"' : ''; // класс для активной категории
                $breadcrumbs .= "<li {$class}>{$link}</li>";
            }
        }
        // если передано наименование товара, добавляем его
        if ($name) {
            $breadcrumbs .= "<li class='active'>$name</li>";
        }
        return $breadcrumbs;

        // работает только если явно задать ключи
        // $array = array('1' => '1','2' => '2','3' => '3', '4'=>'4','5'=>'5');
        /* foreach ($breadcrumbs_array as $alias => $title) {
            if ($title != end($breadcrumbs_array)) {
                // делаем что-либо с каждым элементом
            }
            else {
                // делаем что-либо с последним элементом...
            }
        } */

        /* $end_element = array_pop($breadcrumbs_array);
        foreach ($breadcrumbs_array as $alias => $title) {
            // делаем что-либо с каждым элементом
        }
        // делаем что-либо с последним элементом $end_element */
    }

    // служебный метод
    public static function getParts(array $cats, ?int $id): ?array {
        if (!$id) return null; // если не передан id категории возвращаем false
        $breadcrumbs = [];
        foreach ($cats as $k => $v) {
            // $k - идентификаторы элементов массива
            // $v - массивы (значения элементов массива)
            // если в массиве категорий существует переданный id, в массив хлебных крошек записываем ['алиас категории' => наименование]
            if (isset($cats[$id])) {
                $breadcrumbs[$cats[$id]['alias']] = $cats[$id]['title'];
                $id = $cats[$id]['parent_id']; // в id записываем значение родительской категории
            } else break; // прерываем работу цикла если более не найдено совпадений
        }
        // возвращаем массив хлебных крошек, перевернув элементы в обратном порядке
        return array_reverse($breadcrumbs, true); // true - по умолчанию (сохранять ключи элементов)
    }

}
