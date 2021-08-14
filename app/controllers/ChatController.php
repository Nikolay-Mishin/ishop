<?php
// Контроллер категорий

namespace app\controllers;

use app\widgets\chat\Chat;
use app\models\Breadcrumbs;

class ChatController extends AppController {

	public function indexAction(): void {
		if ($this->isAjax()) {
			$action = $_GET['action'] ?? null;
			$result = Chat::$action();
			debug($result);
			//exit(json_encode($result));
			die;
		}

		$breadcrumbs = Breadcrumbs::getBreadcrumbs(null, 'Чат'); // хлебные крошки;

		$this->setStyle('css/widgets/chat'/*, 'css/widgets/chat'*/);
		$this->setScript('js/widgets/chat'/*, 'js/widgets/chat'*/);
		$this->setMeta('Чат');
		$this->set(compact('breadcrumbs'));
	}

}
