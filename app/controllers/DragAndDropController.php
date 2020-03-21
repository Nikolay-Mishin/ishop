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

	public function exampleAction(){
		$this->setStyle('/jquery-ui-1.12.1/themes/sunny/jquery-ui', '/dragAndDrop/dragAndDrop');
		$this->setScript('/dragAndDrop/dragAndDrop-example');
		$this->setMeta('Drag and Drop - Example');
	}

	public function mainAction(){
		$this->setStyle('/dragAndDrop/sort');
		$this->setScript('/dragAndDrop/dragAndDrop', '/dragAndDrop/dragAndDrop-module');
		$this->setMeta('Drag and Drop - Module');
	}

	public function sortAction(){
		$this->setStyle('/dragAndDrop/sort');
		$this->setScript('/dragAndDrop/sort', '/dragAndDrop/sort-list');
		$this->setMeta('Sort DOM');
	}

}
