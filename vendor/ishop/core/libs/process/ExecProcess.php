<?php

namespace ishop\libs\process;

class ExecProcess {
    
    private $pid;
    private $command;
    private $root;

    // Массив с полными путями к папкам, которые должны быть в Path (в твоём случае это папка `bin` в Node и `/node_modiles/.bin`)
    private $envList = [];

    // Массив дополнительных команд, которые я использовал для дебага окружения. Тебе может не пригодиться.
    private $additional = [];

    private function runCom() {
        $command = "";
        if (!empty($this->envList)) {
            $command .= 'export PATH=$PATH:'.implode(":", $this->envList).'; ';
        }
        if (!empty($this->root)) {
            $command .= 'cd '.$this->root.'; ';
        }
        if (!empty($this->command)) {
            $command .= 'nohup ' . $this->command . ' > /dev/null 2>&1 & echo $!;';
        }
        if (!empty($this->additional)) {
            $command .= implode("; ", $this->additional);
        }
        exec($command, $op);
        $this->pid = intval($op[0]);
    }

    public function setPid($pid) {
        $this->pid = $pid;
    }

    public function setRoot($root) {
        $this->root = $root;
    }

    public function setEnv($envList) {
        $this->envList = $envList;
    }

    public function setCommand($commandLine) {
        $this->command = $commandLine;
    }

    public function setAdditional($additionalCommands) {
        $this->additional = $additionalCommands;
    }

    public function getPid() {
        return $this->pid;
    }

    public function status() {
        if(empty($this->pid)) return false;
        $command = 'ps -p ' . $this->pid;
        exec($command, $op);
        return isset($op[1]);
    }

    public function start() {
        $this->runCom();
    }

    public function stop() {
        if(empty($this->pid)) return true;
        $command = 'kill ' . $this->pid;
        exec($command);
        return !$this->status();
    }

}
