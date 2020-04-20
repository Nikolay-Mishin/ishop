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
		$this->setMeta('Drag and Drop - Example');
		$this->setStyle('/jquery-ui-1.12.1/themes/sunny/jquery-ui', '/dragAndDrop/dragAndDrop');
		$this->setScript('/dragAndDrop/dragAndDrop-example');
	}

	public function mainAction(){
		$this->setMeta('Drag and Drop - Module');
		$this->setStyle('/dragAndDrop/sort');
		$this->setScript('/dragAndDrop/dragAndDrop-module');
	}

	public function sortAction(){
		$this->setMeta('Sort DOM');
		$this->setStyle('/dragAndDrop/sort');
		$this->setScript('/dragAndDrop/sort', '/dragAndDrop/sort-list');
	}

}
