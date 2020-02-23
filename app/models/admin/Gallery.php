<?php

namespace app\models\admin;

use app\models\admin\Product;
use app\models\AppModel;

class Gallery extends AppModel {

	// метод удаления базовой картинки из БД и с сервера
	public static function deleteSingle($id, $src, $table = 'product'){
		$product = Product::getById($id); // загружаем данные товара из БД
		$product->img = 'no_image.jpg'; // записываем путь к заглушке
		return \R::store($product) ? deleteImg($src) : '0'; // удаляем картинку из БД
	}

	// метод удаления картинок галлереи из БД и с сервера
	public static function deleteGallery($id, $src, $table = 'gallery', $idCol = 'product_id', $srcCol = 'img'){
		// удаляем картинку из БД
		return \R::exec("DELETE FROM $table WHERE $idCol = ? AND $srcCol = ?", [$id, $src]) ? deleteImg($src) : '0';
	}

	// метод удаления картинок галлереи из БД и с сервера
	public static function deleteImg($src){
		@unlink(WWW . "/images/$src"); // удаляем картинку с сервера (@ - заглушка ошибок с правами и тд)
		return 1; // в качестве ответа отправляем '1'
	}

}
