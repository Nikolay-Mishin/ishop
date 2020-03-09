<?php

namespace app\widgets\comment;

use app\widgets\menu\Menu;
use app\widgets\editor\Editor;
use ishop\App; // подключаем класс базовый приложения

class Comment extends Menu {

	protected $isAjax = false;
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
	protected $title = 'Комментарии';
	protected $count;

	public function __construct($options = []){
		debug($options);
		parent::__construct($options);
		debug($this);
		$this->count = count($this->data);
		if(!$this->isAjax){
			$this->editor_options['id'] = $this->id;
			App::$app->setProperty('editor_options', $this->editor_options);
			$this->editor = new Editor($this->editor_options);
			debug($this->isAjax);
		}else{
			//debug($this);
		}
	}

	public function __toString(){
		return !$this->isAjax ? $this->getComments(parent::__toString(), $this->editor) : 'Comment';
	}

	public function run(){
		$this->getTree();
	}

	// получает html-разметку
	protected function getComments($comments, $editor = null){
		ob_start(); // включаем буферизацию
		require $this->comments_tpl; // подключаем шаблон
		return ob_get_clean(); // получаем контент из буфера и очищаем буфер
	}

	protected function getTitle(){
		return $this->count ? "{$this->title} ({$this->getSpan()})" : $this->getSpan();
	}

	private function getSpan(){
		return '<span id="comments-count">' .($this->count ?: null). '</span>';
	}

}
