<?php

namespace app\widgets\editor;

use app\widgets\menu\Menu;

class Editor extends Menu {

	protected $isMenu = false;
	protected $tpl = __DIR__ . '/editor_tpl.php'; // шаблон
	protected $label = 'Контент'; // наименование поля
	protected $isRequired = false;
	protected $id = 'editor';
	protected $cols = 80;
	protected $rows = 10;

}
