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
			$result = Chat::$action();
			Cache::set('chat', $result, 0);
			exit($result[$action]);
		}

		$breadcrumbs = Breadcrumbs::getBreadcrumbs(null, 'Чат'); // хлебные крошки;

		$processLog = Cache::get('chat');
		if ($processLog) debug($processLog);

		$this->setStyle('css/widgets/chat'/*, 'css/widgets/chat'*/);
		$this->setScript('js/widgets/chat'/*, 'js/widgets/chat'*/);
		$this->setMeta('Чат');
		$this->set(compact('breadcrumbs'));
	}

}
