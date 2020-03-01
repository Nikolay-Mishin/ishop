<?php

namespace ishop\base;

abstract class Select extends Sql {

	protected static $table = null;

	public static function getTable(){
		return static::$table = static::$table ?? self::getTabelName();
	}

	// получает общее записей
	public static function getCount(){
		return \R::count(self::getTable());
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
