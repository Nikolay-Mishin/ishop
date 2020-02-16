<?php

namespace app\models\admin;

use app\models\AppModel;

class FilterGroup extends AppModel{

	// переопределяем аттрибуты родительской модели
	public $attributes = [
		'title' => '',
	];

	// переопределяем правила валидации формы родительской модели
	public $rules = [
		'required' => [
			['title'],
		],
	];

}
