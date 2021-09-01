<?php
// Контроллер категорий

namespace app\controllers;

use ishop\libs\process\GulpProcess;

class ProcessController extends AppController {

	public function startGulpProcessAction() {
        $gulpProcess = new GulpProcess();
        return $gulpProcess->start();
    }

    public function stopGulpProcessAction() {
        $gulpProcess = new GulpProcess();
        return $gulpProcess->stop();
    }

    public function getGulpStatusAction() {
        $gulpProcess = new GulpProcess();
        $this->jsonData["is_active"] = $gulpProcess->isActive();
        return true;
    }

}
