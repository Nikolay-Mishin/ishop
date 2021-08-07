<?php

namespace app\models;

class Comment extends AppModel {

	public static $pagination; // пагинация
	protected string $select = 'comment.*, user.name, user.avatar, user.login';
	protected string $join = 'user ON comment.user_id = user.id';
	protected string $where = 'product_id = ?';
	protected string $group = 'comment.id';
	protected string $sort = '';
	protected string $order = 'comment.id';
	//protected $limit = '1';

	// аттрибуты модели (параметры/поля формы)
	public array $attributes = [
		'content' => '',
		'product_id' => '',
		'user_id' => '',
		'rate' => 0,
		'parent_id' => 0,
	];

	// набор правил для валидации
	public array $rules = [
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
			['parent_id'],
		]
	];

	public function __construct(array $data = [], array $attrs = [], string $action = 'save') {
		if (!$data) return false;

		if ($action == 'save') {
			$data['product_id'] = !empty($data['product_id']) ? (int)$data['product_id'] : null;
			$data['user_id'] = !empty($data['user_id']) ? (int)$data['user_id'] : null;
			$data['parent_id'] = !empty($data['parent_id']) ? (int)$data['parent_id'] : null;
		} else {
			$this->setRequired($data, 'rate');
		}
		// вызов родительского конструктора, чтобы его не затереть (перегрузка методов и свойств)
		parent::__construct($data, $attrs, $action);
		// сохраняем товар в БД
		if ($this->id){
			$_SESSION['success'] = $action == 'update' ? 'Изменения сохранены' : 'Комментарий добавлен';
		}
	}

	public static function getByProductId(int $id): array {
		/*
		SELECT comment.*, user.name, user.avatar, user.login
		FROM `comment`
		JOIN user ON comment.user_id = user.id
		WHERE product_id = ?
		GROUP BY comment.id
		ORDER BY comment.id
		*/
		return \R::getAssoc(self::getSql(), [$id]);
	}

	public static function getRate(int $id): string {
		return \R::getCell("SELECT rate FROM comment WHERE id = ?", [$id]);
	}

}
