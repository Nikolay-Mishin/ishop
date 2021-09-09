<?php
// Контроллер категорий

namespace app\controllers;

use app\widgets\chat\Chat;
use app\models\Breadcrumbs;
use ishop\Cache;

class ChatController extends AppController {

	public function indexAction(): void {
		if ($this->isAjax() && $action = $_GET['action'] ?? null) exit(Chat::$action());

		$breadcrumbs = Breadcrumbs::getBreadcrumbs(null, 'Чат'); // хлебные крошки;

		$log = Cache::get('chat');
		$processList = Cache::get('processList');
		debug($log);
		debug($processList);

		$this->setStyle('widgets/chat'/*, 'widgets/chat'*/);
		$this->setScript('widgets/chat'/*, 'widgets/chat'*/);
		$this->setMeta('Чат');
		$this->set(compact('breadcrumbs'));
	}

}
