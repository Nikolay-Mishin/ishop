<?php

namespace app\widgets\chat;

require_once CONF.'/chat.php';

use ishop\libs\Process;

class Chat {

	public static function start(): bool {
		return Process::add('php '.SERVER, 'chat') ? true : false;
	}

	public static function stop(): bool {
		return Process::killProc('chat');
	}

	public static function clean(): bool {
		return Process::clean();
	}

	public static function server(): bool {
		return Process::add('php '.CHAT_SERVER, 'chat') ? true : false;
	}

}
