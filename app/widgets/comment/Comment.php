<?php

namespace app\widgets\comment;

use app\widgets\menu\Menu;
use app\widgets\editor\Editor;

class Comment extends Menu {

	use \ishop\traits\T_Ajax;

	protected $isAjax = false;
	protected $isMenu = true;
	protected $tpl = __DIR__ . '/comment_tpl.php'; // шаблон
	protected $comments_tpl = __DIR__ . '/comments_tpl.php'; // шаблон комментария
	protected $form_id = 'comment_add';
	protected $editor = true;
	protected $editor_id = 'comment_editor';
	protected $editor_options = [
		'tpl' => __DIR__ . '/editor_tpl.php',
		'label' => 'Новый комментарий',
		'isRequired' => true
	];
	protected $title = 'Комментарии';
	public $count;
	protected $container = 'span'; // контейнер
	protected $container_id = 'comments-count';

	public function __construct($options = []){
		parent::__construct($options);
		$this->count = count($this->data) ?: null;
		$this->editor_options['id'] = $this->editor_id;
		$this->meta['form_id'] = $this->meta['form_id'] ?? $this->form_id;
		$this->editor_options['meta'] = $this->meta;
		$this->getEditor();
	}

	public function __toString(){
		return !$this->isAjax ? $this->getComments(parent::__toString(), $this->editor) : parent::__toString();
	}

	public function getEditor(){
		return $this->editor = $this->editor ? new Editor($this->editor_options) : null;
	}

	public function getCount(){
		return $this->count;
	}

	protected function run(){
		$this->getTree();
	}

	// получает html-разметку
	protected function getComments($comments, $editor = null){
		ob_start(); // включаем буферизацию
		require $this->comments_tpl; // подключаем шаблон
		return ob_get_clean(); // получаем контент из буфера и очищаем буфер
	}

	protected function getTitle(){
		return $this->count ? "{$this->title} ({$this->getTitleHtml()})" : $this->getTitleHtml();
	}

	private function getTitleHtml(){
		return "<$this->container id=$this->container_id>$this->count</$this->container>";
	}

	public function getInfo(){
		return [
			'data' => $this->data, 'editor' => $this->editor, 'type' => gettype($this->editor), 'options' => $this->editor_options,
			'form_id' => $this->meta('form_id'), 'id' => $this->meta('id'), 'parent' => $this->meta('parent_id')
		];
	}

}
