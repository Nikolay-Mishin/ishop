<?php

namespace ishop\libs;

use ishop\Cache;

class Process {
    
    public static ?self $curr_process = null;
    public static array $isRun = [];
    
    public int $terminate_after = 5; // seconds after process is terminated
    public $process;
    public ?string $pkey;
    public int $pid;
    public array $pstatus;

    public string $cmd;
    public array $descriptorspec = array(
        0 => array("pipe", "r"),  // stdin - канал, из которого дочерний процесс будет читать
        //1 => array("pipe", "w"),  // stdout - канал, в который дочерний процесс будет записывать
        //2 => array("file", "error-output.txt", "a+") // stderr - файл для записи
    );
    public ?array $pipes;
    // Рабочая директория команды. Это должен быть абсолютный путь к директории или null, если требуется использовать директорию по умолчанию (рабочая директория текущего процесса PHP).
    public string $cwd = '/tmp';
    // Массив переменных окружения для запускаемой команды или null, если требуется использовать то же самое окружение, что и у текущего PHP-процесса.
    public array $env = array('some_option' => 'aeiou');

    public string $output;
    public array $result = [];

    public function __construct(string $cmd, ?string $pkey = null, ?array $descriptorspec = null, ?string $cwd = null, ?array $env = null, ?int $terminate_after = null) {
        $this->pkey = $pkey;
        $this->cmd = $cmd;
        $this->descriptorspec = $descriptorspec ?? $this->descriptorspec;
        $this->cwd = $cwd ?? $this->cwd;
        $this->env = $env ?? $this->env;
        $this->terminate_after = $this->terminate_after ?? $terminate_after;

        $this->process = proc_open($this->cmd, $this->descriptorspec, $this->pipes, /*$this->cwd, $this->env*/);

        //usleep($this->terminate_after * 1000000); // wait for 5 seconds

        if (is_resource($this->process)) {
            // $pipes теперь выглядит так:
            // 0 => записывающий обработчик, подключённый к дочернему stdin
            // 1 => читающий обработчик, подключённый к дочернему stdout
            // Вывод сообщений об ошибках будет добавляться в error-output.txt

            $write = '<?php print_r($_ENV); ?>';
            //fwrite($this->pipes[0], $write);
            //$this->output = stream_get_contents($this->pipes[1]);
            //debug($this->output);

            $this->pstatus = proc_get_status($this->process);
            $this->pid = $this->pstatus['pid'];

            // terminate the process
            $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
            //debug('Process terminated after: '.$time);
            
            /*
            Результатом выполнения данного примера будет что-то подобное:
            Array
            (
                [some_option] => aeiou
                [PWD] => /tmp
                [SHLVL] => 1
                [_] => /usr/local/bin/php
            )
            */
        }
    }

    public static function add(string $cmd, ?string $pkey = null, ?array $descriptorspec = null, ?string $cwd = null, ?array $env = null, ?int $terminate_after = null): ?self {
        if (self::getProcess($pkey)) return null;
        self::$curr_process = new self($cmd, $pkey, $descriptorspec, $cwd, $env, $terminate_after);
        debug(self::$curr_process);
		self::$curr_process->setCache();
		return self::$curr_process;
    }
    
    public static function killProc(int|string $pkey): bool {
        if ($process = self::getProcess($pkey)) {
            $process->kill();
            self::$isRun[] = self::isRun($process->pid);
            $process->_isRun = self::$isRun;
            self::$curr_process = $process;
            return true;
        }
        return false;
    }

    public static function getProcess(int|string $pkey): ?self {
        return self::getProcessList()[$pkey] ?? null;
    }

    public static function getProcessList(): array {
        return Cache::get('processList') ?? [];
    }

    public static function clean(): bool {
        if ($process_list = self::getProcessList()) {
            foreach ($process_list as $process) {
                $process->kill();
            }
            Cache::delete('processList');
        }
        return true;
    }

    /** tasklist [/s <computer> [/u [<domain>\]<username> [/p <password>]]] [{/m <module> | /svc | /v}] [/fo {table | list | csv}] [/nh] [/fi <filter> [/fi <filter> [ ... ]]]
    * /fo {table | list | csv}	Указывает формат, используемый для выходных данных. Допустимые значения: Table, List и CSV. Формат выходных данных по умолчанию — Table.
    * /Fi <filter>	Указывает типы процессов, включаемых в запрос или исключаемых из него. Можно использовать более одного фильтра или использовать подстановочный знак ( \ ) для указания всех задач или имен изображений. Допустимые фильтры перечислены в разделе имена фильтров, операторы и значения этой статьи.
    */
    public static function isRun(int $pid): bool {
        if (stripos(php_uname('s'), 'win') > -1) {
            exec("tasklist /fo list /fi \"pid eq $pid\"", $out);
            if (!isset(self::$isRun['out'])) self::$isRun['out'] = [];
            self::$isRun['out'][] = $out;
            if (count($out) > 1) {
                return true;
            }
        }
        elseif (\posix_kill((int) $pid, 0))  return true;
        return false;
    }

    protected static function checkOS(): bool {
        return stripos(php_uname('s'), 'win') > -1;
    }

    public function setCache($del = false): bool {
        $processList = self::getProcessList();
        if (!$del) $processList[$this->getPid()] = $this;
        else unset($processList[$this->getPid()]);
		return Cache::set('processList', $processList, 0, true);
	}

    public function getPid(): int|string {
        return $this->pkey ?? $this->pid;
    }

    /**
    * You can use the proc_ functions to get better control.
    * You will find it in the manual. Below you find code you might find useful.
    * It works only under windows, you need a different kill routine on linux.
    * he script terminates the (else endless running) ping process after approximatly 5 seconds.
    */
    /**
    * taskkill [/s <computer> [/u [<domain>\]<username> [/p [<password>]]]] {[/fi <filter>] [...] [/pid <processID> | /im <imagename>]} [/f] [/t]
    * /f	Указывает, что процессы принудительно завершены. Этот параметр не учитывается для удаленных процессов; все удаленные процессы принудительно завершены.
    * /t	Завершает указанный процесс и все дочерние процессы, запущенные этим процессом.
    * /PID <processID>	Указывает идентификатор процесса для завершения процесса.
    */
    public function kill(): bool {
        self::$isRun = [self::isRun($this->pid)];
        if (!self::isRun($this->pid)) return true;

        if ($process = self::getProcess($this->getPid())) $process->setCache(true);

        // вместо proc_terminate($this->process);
        //\posix_kill($this->pid, SIGKILL);
        stripos(php_uname('s'), 'win') > -1 ? exec("taskkill /f /t /pid $this->pid", $this->result) : exec("kill -9 $this->pid", $this->result);
        return true;

		//if (is_resource($this->process)) {
		//    $result = proc_terminate($this->process);
		//    // Важно закрывать все каналы перед вызовом proc_close во избежание мёртвой блокировки
		//    foreach ($this->pipes as $pipe) {
		//        if (is_resource($pipe)) fclose($pipe);
		//    }
		//    $result_2 = proc_close($this->process);
		//    $this->result = [$result, $result_2];
		//    return true;
		//}
    }

}
