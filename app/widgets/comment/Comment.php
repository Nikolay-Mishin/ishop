<?php

namespace app\widgets\comment;

use app\widgets\menu\Menu;
use app\widgets\editor\Editor;
use ishop\App; // подключаем класс базовый приложения

class Comment extends Menu {

	protected $isMenu = true;
	protected $tpl = __DIR__ . '/comment_tpl.php'; // шаблон
	protected $comments_tpl = __DIR__ . '/comments_tpl.php'; // шаблон комментария
	protected $id;
	protected $editor;
	protected $editor_options = [
		'tpl' => __DIR__ . '/editor_tpl.php',
		'label' => 'Новый комментарий',
		'isRequired' => true
	];

	public function __construct($options = []){
		parent::__construct($options);
		$this->editor_options['id'] = $this->id;
		App::$app->setProperty('editor_options', $this->editor_options);
		$this->editor = new Editor($this->editor_options);
	}

	public function __toString(){
		return $this->getComments($this->editor, parent::__toString());
	}

	public function run(){
		$this->getTree();
	}

	// получает html-разметку
	protected function getComments($editor, $comments){
		ob_start(); // включаем буферизацию
		require $this->comments_tpl; // подключаем шаблон
		return ob_get_clean(); // получаем контент из буфера и очищаем буфер
	}

}