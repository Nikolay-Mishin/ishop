<?php

namespace app\widgets\chat;

require_once CONF.'/chat.php';

use ishop\Cache;
use ishop\libs\Process;

class Chat {

	public static function start(): bool {
		$process = Process::add('php '.SERVER, 'chat');
		self::log('start');
		return $process ? true : false;
	}

	public static function stop(): bool {
		$process = Process::killProc('chat');
		self::log('stop');
		return $process;
	}

	public static function clean(): bool {
		return Process::clean();
	}

	public static function log($action): void {
		$log = Cache::get('chat') ?? [];
		$log[$action] = Process::$curr_process;
		Cache::set('chat', $log, 0, true);
	}

}
