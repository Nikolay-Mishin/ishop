<?php

namespace app\widgets\editor;

use app\widgets\menu\Menu;

class Editor extends Menu {

	protected bool $isMenu = false;
	protected string $tpl = __DIR__ . '/editor_tpl.php'; // шаблон
	protected string $label = 'Контент'; // наименование поля
	protected bool $isRequired = false;
	protected string $id = 'editor';
	protected int $cols = 80;
	protected int $rows = 10;

}
