<?php
// Модель продукта - карточки товара
// сохранение в куки и получение просмотренных товаров

namespace app\models;

class Product extends AppModel {

	protected function getProtectAttrs(){ return 'getProtectAttrs'; }
	private function getPrivateAttrs(){ return 'getPrivateAttrs'; }

	// получаем данные товара из БД
	public static function getById($id){
		return \R::findOne('product', 'id = ?', [$id]);
	}

	// добавляет просмотренные товары
	public function setRecentlyViewed($id){
		// в куках храним только id товара
		$recentlyViewed = $this->getAllRecentlyViewed(); // получаем все просмотренные товары
		// если нет просмотренных товаров, записываем его в куки
		if(!$recentlyViewed){
			setcookie('recentlyViewed', $id, time() + 3600*24, '/');
		}else{
			$recentlyViewed = explode('.', $recentlyViewed); // разбиваем строку по разделителю '.' и получаем массив
			// если в массиве есть id запрошенного товара, записываем его в куки
			if(!in_array($id, $recentlyViewed)){
				$recentlyViewed[] = $id; // добавляем в конец массива запрошенный товар
				$recentlyViewed = implode('.', $recentlyViewed); // преобразуем массив в строку с раздителем '.'
				setcookie('recentlyViewed', $recentlyViewed, time() + 3600*24, '/'); // записываем в куки полученную строку
			}
		}
	}

	// получаем последние просмотренные товары (3)
	public function getRecentlyViewed(){
        $this->addProtectProperties('bean => set', 'tbl');
		$this->addProtectMethods('getProps');
		// если в куках есть просмотренные товары, возвращаем срез из 3 элементов массива, иначе - false
		if(!empty($_COOKIE['recentlyViewed'])){
			$recentlyViewed = $_COOKIE['recentlyViewed']; // просмотренные товары из кук
			$recentlyViewed = explode('.', $recentlyViewed); // формируем массив из строки по раздителем '.'
			return array_slice($recentlyViewed, -3); // возвращаем срез из 3 элементов с конца
		}
		return false;
	}

	// получаем все просмотренные товары из кук
	public function getAllRecentlyViewed(){
		// если в куках есть просмотренные товары, возвращаем ее, иначе - false
		if(!empty($_COOKIE['recentlyViewed'])){
			return $_COOKIE['recentlyViewed'];
		}
		return false;
	}

}
