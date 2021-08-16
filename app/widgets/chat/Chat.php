<?php

namespace app\widgets\chat;

require_once CONF.'/chat.php';

use ishop\libs\Process;

class Chat {

	public static function start(): array {
		$process = Process::add('php '.SERVER, 'chat');
		return ['result' => $process ? true : false, 'log' => Process::$log, 'processList' => Process::getProcessList()];
	}

	public static function stop(): array {
		$result = Process::killProc('chat');
		return ['result' => $result, 'log' => Process::$log, 'processList' => Process::getProcessList()];
	}

}
