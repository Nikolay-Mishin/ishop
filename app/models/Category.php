<?php
// Модель категорий

namespace app\models;

use ishop\App;

class Category extends AppModel {

    // генерирует список id (1, 2, 3...) вложенных категорий по id запрошенной категории
    public function getIds($id){
        $cats = App::$app->getProperty('cats'); // получаем категории из контейнера
        $ids = null; // значение по умолчанию для списка вложенных категорий
        // рекурсивно проходим по списку категорий
        foreach($cats as $k => $v){
            // если parent_id данной категории совпадает с id запрошенной категории, значит данная категория является дочерней
            if($v['parent_id'] == $id){
                $ids .= $k . ','; // формируем строку со списком вложенных категорий
                // ищем вложенные категории для найденной дочерней категории
                $ids .= $this->getIds($k); // рекурсивно вызываем функцию и передаем параметром id дочерней категории
            }
        }
        return $ids;
    }

}

/*
$ids - 4,6,7,5,8,9,10,1
* 1 => 4,5
* 4 => 6,7
* 5 => 8,9,10

=> $cats
Array
(
    [1] => Array
        (
            [title] => Men
            [alias] => men
            [parent_id] => 0
            [keywords] => Men
            [description] => Men
        )

    [4] => Array
        (
            [title] => Электронные
            [alias] => elektronnye
            [parent_id] => 1
            [keywords] => Электронные
            [description] => Электронные
        )

    [5] => Array
        (
            [title] => Механические
            [alias] => mehanicheskie
            [parent_id] => 1
            [keywords] => mehanicheskie
            [description] => mehanicheskie
        )

    [6] => Array
        (
            [title] => Casio
            [alias] => casio
            [parent_id] => 4
            [keywords] => Casio
            [description] => Casio
        )

    [7] => Array
        (
            [title] => Citizen
            [alias] => citizen
            [parent_id] => 4
            [keywords] => Citizen
            [description] => Citizen
        )

    [8] => Array
        (
            [title] => Royal London
            [alias] => royal-london
            [parent_id] => 5
            [keywords] => Royal London
            [description] => Royal London
        )

    [9] => Array
        (
            [title] => Seiko
            [alias] => seiko
            [parent_id] => 5
            [keywords] => Seiko
            [description] => Seiko
        )

    [10] => Array
        (
            [title] => Epos
            [alias] => epos
            [parent_id] => 5
            [keywords] => Epos
            [description] => Epos
        )
)
*/