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

class DragAndDropController extends AppController {

	public function indexAction(){
		$this->setStyle('/jquery-ui-1.12.1/themes/sunny/jquery-ui');
		$this->setMeta('Drag and Drop');
	}

}
