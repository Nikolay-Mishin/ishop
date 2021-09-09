<?php
// Контроллер категорий

namespace app\controllers;

use app\widgets\chat\Chat;
use app\models\Breadcrumbs;
use ishop\Cache;
use ishop\libs\Process;

class ChatController extends AppController {

	public function indexAction(): void {
		if ($this->isAjax() && $action = $_GET['action'] ?? null) {
			$log = Cache::get('chat') ?? [];
			$result = Chat::$action();
			if ($action == 'clean') exit($result);
			$log[$action] = Process::$log;
			Cache::set('chat', $log, 0, true);
			Cache::set('processList', Process::getProcessList(), 0, true);
			exit($result);
		}

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
