<?php

namespace app\models;

class Comments extends AppModel {

	// получаем информацию по модификатору данного товара
	public static function getByProductId($id){
		return \R::getAll('comments', 'product_id = ?', [$id]);
	}

}
