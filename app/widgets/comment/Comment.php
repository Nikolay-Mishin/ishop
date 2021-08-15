<?php

namespace app\widgets\comment;

use app\widgets\menu\Menu;
use app\widgets\editor\Editor;

class Comment extends Menu {

	use \ishop\traits\T_Ajax;

	protected bool $isMenu = true;
	protected string $tpl = __DIR__ . '/comment_tpl.php'; // шаблон
	protected string $comments_tpl = __DIR__ . '/comments_tpl.php'; // шаблон комментария
	protected string $form_id = 'comment_add';
	protected ?Editor $editor = null;
	protected string $editor_id = 'comment_editor';
	protected array $editor_options = [
		'tpl' => __DIR__ . '/editor_tpl.php',
		'label' => 'Новый комментарий',
		'isRequired' => true
	];
	protected string $title = 'Комментарии';
	public ?int $count;
	protected string $container = 'span'; // контейнер
	protected string $container_id = 'comments-count';

	public function __construct($options = []) {
		parent::__construct($options);
		$this->count = count($this->data) ?: null;
		$this->editor_options['id'] = $this->editor_id;
		$this->meta['form_id'] = $this->meta['form_id'] ?? $this->form_id;
		$this->editor_options['meta'] = $this->meta;
		$this->getEditor();
	}

	public function __toString(): string {
		return !$this->isAjax ? $this->getComments(parent::__toString(), $this->editor) : parent::__toString();
	}

	public function getEditor(): ?Editor {
		return $this->editor = $this->editor ?? new Editor($this->editor_options);
	}

	public function getCount(): int {
		return $this->count;
	}

	protected function run(): void {
		$this->getTree();
	}

	// получает html-разметку
	protected function getComments(string $comments, ?Editor $editor = null): string {
		return $this->getContents($this->comments_tpl, compact('comments', 'editor')); // получаем контент из буфера
	}

	protected function getTitle(): string {
		return $this->count ? "{$this->title} ({$this->getTitleHtml()})" : $this->getTitleHtml();
	}

	private function getTitleHtml(): string {
		return "<$this->container id=$this->container_id>$this->count</$this->container>";
	}

	public function getInfo(): array {
		return [
			'data' => $this->data, 'editor' => $this->editor, 'type' => gettype($this->editor), 'options' => $this->editor_options,
			'form_id' => $this->meta('form_id'), 'id' => $this->meta('id'), 'parent' => $this->meta('parent_id')
		];
	}

}
