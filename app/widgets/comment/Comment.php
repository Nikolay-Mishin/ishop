<?php

namespace app\widgets\comment;

use app\widgets\editor\Editor;
use app\widgets\menu\Menu;

class Comment extends Menu {

	public $tpl; // шаблон
	public $comment_tpl; // шаблон комментария
	public $id;
	public $editor;

	public function __construct($comments, $id, $tpl = ''){
		$this->editor = new Editor(null, 'Новый комментарий', true);
		list($this->data, $this->id, $this->tpl) = [$comments, $id, $tpl ?: __DIR__ . '/comments_tpl.php'];
		$this->tpl = $tpl ?: __DIR__ . '/comments_tpl.php'; // подключаем шаблон виджета
		$this->run();
	}

	public function __toString(){
		return $this->getHtml();
	}

	public function run(){
		debug($this->getTree());
		return $this->getTree();
	}

	// получает html-разметку
	protected function getCommentTpl(){
		ob_start(); // включаем буферизацию
		require $this->comment_tpl; // подключаем шаблон
		return ob_get_clean(); // получаем контент из буфера и очищаем буфер
	}

	// получает html-разметку
	protected function getHtml(){
		ob_start(); // включаем буферизацию
		require $this->tpl; // подключаем шаблон
		return ob_get_clean(); // получаем контент из буфера и очищаем буфер
	}

}
