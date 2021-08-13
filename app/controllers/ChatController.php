<?php
// Контроллер категорий

namespace app\controllers;

use app\widgets\chat\Chat;

class ChatController extends AppController {

	public function indexAction(): void {
		$this->setStyle(['css/widgets/chat']);
		$this->setScript(['js/widgets/chat']);
		$this->setMeta('Чат');
		if ($this->isAjax) {
			$action = $_POST['action'] ?? null;
			Chat::$action();
		}
	}

}
