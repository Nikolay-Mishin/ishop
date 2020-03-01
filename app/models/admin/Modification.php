<?php

namespace app\models\admin;

use app\models\Modification as baseModification;

class Modification extends baseModification {

	// получаем информацию по модификатору
	public static function getAll(){
		return \R::findAll('modification');
	}

	// получаем информацию по модификатору
	public static function getById($id){
		return \R::load('modification', $id);
	}

	// получаем информацию по всем модификаторам данного товара
	public static function getByProductId($id){
		return \R::findAll('modification', 'product_id = ?', [$id]);
	}

}
