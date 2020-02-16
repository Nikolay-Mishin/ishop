<?php

namespace app\models\admin;

use app\models\AppModel;

class FilterAttr extends AppModel{

	// переопределяем аттрибуты родительской модели
	public $attributes = [
		'value' => '',
		'attr_group_id' => '',
	];

	// переопределяем правила валидации формы родительской модели
	public $rules = [
		'required' => [
			['value'],
			['attr_group_id'],
		],
		'integer' => [
			['attr_group_id'],
		]
	];

}
