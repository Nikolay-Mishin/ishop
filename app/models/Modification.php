<?php

namespace app\models;

class Modification extends AppModel {

	// получаем информацию по модификатору данного товара
	public static function getModByProductId(int $id, int $product_id): ?\Bean {
		// return $mod_id ? \R::findOne('modification', 'id = ? AND product_id = ?', [$id, $product_id]) : null;
		return \R::findOne('modification', 'id = ? AND product_id = ?', [$id, $product_id]);
	}

}
