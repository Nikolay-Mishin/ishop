<?php

namespace app\models\admin;

use app\models\AppModel;

class Currency extends AppModel{

	// переопределяем аттрибуты родительской модели
	public $attributes = [
		'title' => '',
		'code' => '',
		'symbol_left' => '',
		'symbol_right' => '',
		'value' => '',
		'course' => '',
		'base' => '',
	];

	// переопределяем правила валидации формы родительской модели
	public $rules = [
		'required' => [
			['title'],
			['code'],
			['course'],
		],
		'numeric' => [
			['course'],
		],
	];

}
