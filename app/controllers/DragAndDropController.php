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

	public function exampleAction(): void {
		$this->setMeta('Drag and Drop - Example');
		$this->setStyle('/jquery-ui-1.12.1/themes/sunny/jquery-ui', 'css/dragAndDrop');
		$this->setScript('/dragAndDrop-example');
	}

	public function mainAction(): void {
		$this->setMeta('Drag and Drop - Module');
		$this->setStyle('sort');
		$this->setScript('/dragAndDrop-module');
	}

	public function sortAction(): void {
		$this->setMeta('Sort DOM');
		$this->setStyle('sort');
		$this->setScript('sort', 'sort-list');
	}

}
