<?php

namespace app\models;

class Comment extends AppModel {

	// получаем информацию по модификатору данного товара
	public static function getByAlias($alias){
		return \R::getAll('comment', 'alias = ?', [$alias]);
	}

}
