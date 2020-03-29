<?php

namespace app\widgets\ban;

class Ban {

	/* массив с ip и типом блокировки. в ключе массива IP, в значении тип блокировки */
	public static $ban_ip = [];
	public static $user_ip;
	/* Сообщение при банне ip */
	public static $ban_message = "Для вашего IP (%ip%) доступ к сайту закрыт.";
	/* Предупреждение о возможности бана по ip */
	public static $wrong_message = "Вы предупреждены администратором данного сайта о возможной блокировке вашего IP (%ip%) в случае дальнейшего нарушения правил.";

	public static function getBan(){
		return self::$ban_ip = [
			"195.66.203.247" => "ban", // реальный плохой IP
			"220.94.220.60" => "ban", // реальный плохой IP
			"127.0.0.1" => "wrong" // Test
		];
	}

	/* Функция для почти 100% определения IP адреса посетителя. */
	/* Перебирает все возможные переменные с IP. */
	public static function getIP(){
		if(isset($_SERVER["HTTP_X_REAL_IP"])){
			return self::$user_ip = $_SERVER["HTTP_X_REAL_IP"];
		} elseif(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
			return self::$user_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} elseif(isset($_SERVER["HTTP_CLIENT_IP"])){
			return self::$user_ip = $_SERVER["HTTP_CLIENT_IP"];
		} else{
			return self::$user_ip = $_SERVER["REMOTE_ADDR"];
		}
	}
 
	/* Определяет, что делать с владельцем того или иного ip адреса */
	public static function checkIP(){
		/* разбираем массив на ключ и значение */
		foreach(self::getBan() as $ip => $type){
			// проверяем ip
			if($ip == self::getIP()){
				// если ip совпал то смотрим что делать
				switch($type){
					// предупреждение
					case "wrong":
						return str_replace("%ip%", $ip, self::$wrong_message); // выводим предупреждение
					// блокировка
					case "ban": {
						$fp = fopen(TMP . '/ipaccess.log', 'a+');
						fwrite($fp, date('d.m.Y H:i:s') . " ({$ip}) '{$_SERVER['REQUEST_URI']}'" . PHP_EOL);
						die(str_replace("%ip%", $ip, self::$ban_message)); // Сообщение - доступ закрыт + завершение php
						// break не требуется т.к. дальше уже ничего не выполняется
					}
				}
			}
		}
	}

}
