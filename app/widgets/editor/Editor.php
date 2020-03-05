<?php

namespace app\widgets\editor;

class Editor {

	public $tpl; // шаблон
	public $data; // массив данных
	public $label; // наименование поля
	public $isRequired;
	public $id;
	public $cols;
	public $rows;

	public function __construct($data = null, $label = 'Контент', $isRequired = false, $id = '', $cols = 80, $rows = 10, $tpl = ''){
		$values = [$data, $label, $isRequired, $id ?: 'editor', $cols, $rows];
		list($this->data, $this->label, $this->isRequired, $this->id, $this->cols, $this->rows) = $values;
		$this->tpl = $tpl ?: __DIR__ . '/editor_tpl.php'; // подключаем шаблон виджета фильтров
	}

	public function __toString(){
		return $this->getHtml();
	}

	// получает html-разметку
	protected function getHtml(){
		ob_start(); // включаем буферизацию
		require $this->tpl; // подключаем шаблон
		return ob_get_clean(); // получаем контент из буфера и очищаем буфер
	}

}
