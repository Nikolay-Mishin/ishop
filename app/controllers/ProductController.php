<?php
// Контроллер продукта - карточка товара
/**
 * информация о текущем продукте
 * хлебные крошки
 * связанные товары
 * запись в куки запрошенного товара
 * просмотренные товары
 * галерея
 * модификации */

namespace app\controllers;

use \Exception;

use app\models\Breadcrumbs; // модель хлебных крошек
use app\models\Product; // модель продукта
use app\models\Comment; // модель комментариев

class ProductController extends AppController {

	// экшен вида (отображения) карточки товара
	public function viewAction(){
		$alias = $this->route['alias']; // получаем алиас текущего продукта
		// получаем по алиасу информацию о текущем продукте из БД
		// SELECT `product`.*  FROM `product`  WHERE alias = ? AND status = '1' LIMIT 1
		$product = \R::findOne('product', "alias = ? AND status = '1'", [$alias]);
		// если продукт не найден выбрасываем исключение
		if(!$product){
			throw new Exception('Страница не найдена', 404);
		}

		// хлебные крошки - строка с ссылками на главную и категории (Home / Single)
		$breadcrumbs = Breadcrumbs::getBreadcrumbs($product->category_id, $product->title);

		// связанные товары - с этим товаром покупают также
		// SELECT * FROM related_product JOIN product ON product.id = related_product.related_id WHERE related_product.product_id = ?
		$related = \R::getAll("SELECT * FROM related_product JOIN product ON product.id = related_product.related_id WHERE related_product.product_id = ?", [$product->id]);

		// запись в куки запрошенного товара
		$p_model = new Product(); // объект модели продукта
		$p_model->setRecentlyViewed($product->id); // записываем запрошенный товар в список просмотренных

		// просмотренные товары - последние 3 просмотренных товара
		// ДЗ - сделать ссылку посмотреть все (product/recentlyViewed - ProductController => recentlyViewed())
		$r_viewed = $p_model->getRecentlyViewed(); // получаем просмотренные товары
		$recentlyViewed = null; // переменная для хранения просмотренных товаров, полученных из БД
		// если просмотренные товары получены из кук, получаем их из БД
		if($r_viewed){
			// find запрос типа IN - ищет совпадение в таблице (по заданному полю) из переданных значений (массива)
			// \R::genSlots - формирует подстановку из '?' по числу элементов массива (?,?,?)
			// SQL - SELECT column-names FROM table-name WHERE column-name IN (values)
			// SELECT `product`.*  FROM `product`  WHERE id IN (?,?,?) LIMIT 3
			$recentlyViewed = \R::find('product', 'id IN (' . \R::genSlots($r_viewed) . ') LIMIT 3', $r_viewed);
		}

		// галерея
		// SELECT `gallery`.*  FROM `gallery`  WHERE product_id = ?
		$gallery = \R::findAll('gallery', 'product_id = ?', [$product->id]);

		// модификации - в корзину можно положить 2 вида товара (как базовый, так и его модификацию)
		// часы 1 и часы 1 (silver)
		// SELECT `modification`.*  FROM `modification`  WHERE product_id = ?
		$mods = \R::findAll('modification', 'product_id = ?', [$product->id]);

		$comments = Comment::getByProductId($product->id);

		//debug($p_model->tbl);
		debug($p_model->getProtectAttrs());
		debug($p_model->getPrivateAttrs());
		debug($p_model->__getPrivateAttrs());

		$this->setMeta($product->title, $product->description, $product->keywords);
		// передаем данные в вид карточки товара
		$this->set(compact('product', 'related', 'gallery', 'recentlyViewed', 'breadcrumbs', 'mods', 'comments'));
	}

}
