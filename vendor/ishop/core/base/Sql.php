<?php

namespace ishop\base;

abstract class Sql extends Query {

	protected static $table = null;

	protected static function getTable(){
		return static::$table = self::getTabelName();
	}

	// получает общее записей
	public static function getCount($where = ''){
		debug(self::getTable());
		return \R::count(self::getTable(), $where);
	}

	// получаем список всех записей
	public static function getAll(){
		return \R::findAll(self::getTable());
	}

	// получаем из БД запись по id
	public static function getById($id){
		return \R::load(self::getTable(), $id);
	}

}
