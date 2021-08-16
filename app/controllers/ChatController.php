<?php
// Контроллер категорий

namespace app\controllers;

use app\widgets\chat\Chat;
use ishop\Cache;
use app\models\Breadcrumbs;

class ChatController extends AppController {

	public function indexAction(): void {
		if ($this->isAjax()) {
			$action = $_GET['action'] ?? null;
			$log = Cache::get('chat') ?? [];
			$log[$action] = Chat::$action();
			Cache::set('chat', $log, 0, true);
			exit($log[$action]['result']);
		}

		$breadcrumbs = Breadcrumbs::getBreadcrumbs(null, 'Чат'); // хлебные крошки;

		$log = Cache::get('chat');
		//debug($log);

		$this->setStyle('widgets/chat'/*, 'widgets/chat'*/);
		$this->setScript('widgets/chat'/*, 'widgets/chat'*/);
		$this->setMeta('Чат');
		$this->set(compact('breadcrumbs'));
	}

}
