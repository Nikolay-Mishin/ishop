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

}