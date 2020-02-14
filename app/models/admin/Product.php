<?php

namespace app\models\admin;

use app\models\AppModel;

class Product extends AppModel {

    // переопределяем аттрибуты родительской модели
    public $attributes = [
        'title' => '',
        'category_id' => '',
        'keywords' => '',
        'description' => '',
        'price' => '',
        'old_price' => '',
        'content' => '',
        'status' => '',
        'hit' => '',
        'alias' => '',
    ];

    // переопределяем правила валидации формы родительской модели
    public $rules = [
        'required' => [
            ['title'],
            ['category_id'],
            ['price'],
        ],
        'integer' => [
            ['category_id'],
        ],
    ];

    private $id;
    private $data;

    // экшен изменения фильтров
    // $id - id товара
    // $data - данные товара
    public function editFilter($id, $data){
        $filter = \R::getCol('SELECT attr_id FROM attribute_product WHERE product_id = ?', [$id]); // получаем все фильтры продукта

        // если менеджер убрал фильтры - удаляем их (checkbox)
        if(empty($data['attrs']) && !empty($filter)){
            $this->deleteFilter($id); // выполняем sql-запрос
            return;
        }

        // если фильтры добавляются
        if(empty($filter) && !empty($data['attrs'])){
            $this->addFilter($id, $data); // добавляем фильтры в БД
            return;
        }

        // если изменились фильтры - удалим и запишем новые
        if(!empty($data['attrs'])){
            $result = array_diff($filter, $data['attrs']); // возвращает разницу между массивами
            // если есть разница между массивами, удаляем имеющиеся фильтры продукта и добавляем новые
            if(!$result)
                $this->deleteFilter($id); // удаляем фильтры продукта
                $this->addFilter($id, $data); // добавляем фильтры в БД
            }
        }
    }

    // метод удаления фильтров
    private function deleteFilter($id){
        \R::exec("DELETE FROM attribute_product WHERE product_id = ?", [$id]); // выполняем sql-запрос
    }

    // метод добавления фильтров
    private function addFilter($id, $data){
        $sql_part = ''; // часть sql-запроса
        // формируем sql-запрос
        foreach($data['attrs'] as $v){
            $sql_part .= "($v, $id),";
        }
        $sql_part = rtrim($sql_part, ','); // удаляем конечную ','
        \R::exec("INSERT INTO attribute_product (attr_id, product_id) VALUES $sql_part"); // выполняем sql-запрос
    }

}