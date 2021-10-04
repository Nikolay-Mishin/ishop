<?php

namespace ishop\traits;

trait T_Factory {

	use T_Singletone;

	protected function __construct(){}

	/**
	* Создаёт экземпляр класса заданного типа.
	*
	* @param string $type Тип коллекции
	* @param mixed $args Объекты
	* @return object
	*/
	public static function create(string|object $type, mixed ...$args): object {
	    return self::instance()->_create($type, ...$args);
	}

}
