<?php

namespace app\models;

class Modification extends AppModel {

	// получаем информацию по модификатору данного товара
	public static function getModByProductId($mod_id, $id){
		return $mod_id ? \R::findOne('modification', 'id = ? AND product_id = ?', [$mod_id, $id]) : null;
	}

}
