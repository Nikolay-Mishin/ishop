<?php

namespace app\models;

class Comment extends AppModel {

	public static $pagination; // пагинация
	protected $select = 'comment.*, user.name, user.avatar, user.login';
	protected $join = 'user ON comment.user_id = user.id';
	protected $where = 'product_id = ?';
	protected $group = 'comment.id';
	protected $sort = '';
	protected $order = 'comment.id';
	//protected $limit = '1';

	// аттрибуты модели (параметры/поля формы)
	public $attributes = [
		'content' => '',
		'product_id' => '',
		'user_id' => '',
	];

	// набор правил для валидации
	public $rules = [
		// обязательные поля
		'required' => [
			['content'],
			['product_id'],
			['user_id'],
		],
		// минимальная длина для поля
		'lengthMin' => [
			['content', 1],
		],
		// минимальная длина для поля
		'lengthMax' => [
			['content', 256],
		],
		// число
		'integer' => [
			['product_id'],
			['user_id'],
			['rate'],
		]
	];

	public function __construct($data = [], $attrs = [], $action = 'save'){
		if(!$data) return false;

		if($action == 'save'){
			$data['product_id'] = !empty($data['product_id']) ? (int)$data['product_id'] : null;
			$data['user_id'] = !empty($data['user_id']) ? (int)$data['user_id'] : null;
		}else{
			$this->setAttributes(['rate' => 0]);
			$this->setRequired(array_keys($data));
		}
		// вызов родительского конструктора, чтобы его не затереть (перегрузка методов и свойств)
		parent::__construct($data, $attrs, $action);
		// сохраняем товар в БД
		if($this->id){
			$_SESSION['success'] = $action == 'update' ? 'Изменения сохранены' : 'Комментарий добавлен';
		}
	}

	public static function getByProductId($id){
		return \R::getAssoc(self::getSql(), [$id]);
	}

	public static function getRate($comment_id){
		return \R::getCell("SELECT rate FROM comment WHERE id = ?", [$comment_id]);
	}

}
