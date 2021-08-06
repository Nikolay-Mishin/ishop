<?php

namespace ishop\base;

abstract class Sql extends Query {
	
	// получает общее записей
	public static function getCount(string $where = '') {
		return \R::count(self::getTable(), $where);
	}

	// получаем список всех записей
	public static function getAll() {
		return \R::findAll(self::getTable());
	}

	// получаем из БД запись по id
	public static function getById(int $id) {
		return \R::load(self::getTable(), $id);
	}

}
