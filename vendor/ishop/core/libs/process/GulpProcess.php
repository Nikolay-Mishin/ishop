<?php

namespace ishop\libs\process;

define("NODE_MODULES_BIN", "node_modules");
define("NODE_BIN", "node");

class GulpProcess {
    
    // Путь к файлу, который будет хранить PID твоего Gulp процесса
    private $tempPidFile = TEMP_DIR . "/gulpTaskPid.tmp";

    /** @var  ExecProcess */
    private $execProcess;

    public function __construct() {
        $this->execProcess = new ExecProcess();
        // Начальная директория для запускаемого нами в итоге скрипта (gulp). У меня так сложилось, что она находится выше на один уровень скрипта index.php. Простыми словами, директория, в которой лежит gulpfile.js
        $this->execProcess->setRoot("../");
        // Те самые директории (абсолютные пути) к Node и node_modules для Path
        $this->execProcess->setEnv([
            NODE_MODULES_BIN,
            NODE_BIN,
        ]);
        /* Тестировал. Может пригодиться)
        $this->execProcess->setAdditional([
            "echo \$PATH",
            "pwd",
            "whoami",
            "ps -ela",
            "id",
        ]);
        */
        $this->execProcess->setCommand('gulp');
    }

    public function start() {
        if (!$this->isActive()) {
            $this->execProcess->start();
            $this->setPid();
            return $this->checkStatus();
        }
        return true;
    }

    public function stop() {
        if ($this->isActive()) {
            $this->execProcess->stop();
            $this->clearPid();
        }
        return true;
    }

    public function isActive() {
        return $this->checkStatus();
    }

    private function getPid() {
        if (is_file($this->tempPidFile)) {
            $pid = intval(file_get_contents($this->tempPidFile));
            $this->execProcess->setPid($pid);
            return $pid;
        }
        return null;
    }

    private function setPid() {
        file_put_contents($this->tempPidFile, $this->execProcess->getPid());
    }

    private function clearPid() {
        if (is_file($this->tempPidFile)) {
            unlink($this->tempPidFile);
        }
        $this->execProcess->setPid(null);
    }

    private function checkStatus() {
        $pid = $this->getPid();
        if (!empty($pid)) {
            if ($this->execProcess->status()) {
                return true;
            }
            $this->clearPid();
            return false;
        }
        return false;
    }

}
