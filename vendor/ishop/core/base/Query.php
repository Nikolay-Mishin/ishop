<?php

namespace ishop\base;

abstract class Query {

	protected static $query = '';

	protected static $from = '';
	protected static $select = '';
	protected static $join = '';
	protected static $where = '';
	protected static $group = '';
	protected static $sort = '';
	protected static $order = '';
	protected static $limit = '';

	protected static $update = '';
	protected static $set = '';

	protected static $insert = '';
	protected static $into = '';
	protected static $values = '';

	protected static $delete = '';

	// возвращает имя таблицы в БД на основе имени модели (thisMethodName => this_method_name)
	public static function getTabelName(){
		return lowerCamelCase(getClassShortName(get_called_class()));
	}

	protected static function getSql($action = 'select'){
		if(static::$select){
			static::select()::from()::join()::where()::group()::order()::limit();
			static::$query = static::$select . static::$from . static::$join . static::$where . static::$group . static::$order . static::$limit;
		}
		elseif(static::$set){
			static::update_sql()::set()::where();
			static::$query = static::$update . static::$set . static::$where;
		}
		elseif(static::$into && static::$values){
			static::insert()::into()::values();
			static::$query = static::$insert . static::$into . static::$values;
		}
		elseif(static::$delete){
			static::delete_sql()::where();
			static::$query = static::$delete . static::$where;
		}
		return static::$query;
	}

	protected static function select($select = null){
		static::$select = "SELECT " . ($select ?: static::$select);
		return static::class;
	}

	protected static function from($from = ''){
		static::$from = PHP_EOL . "FROM `" . ($from ?: (static::$from ?: self::getTabelName())) . '`';
		return static::class;
	}

	protected static function join($join = ''){
		$join = explode(', ', $join ?: static::$join);
		$sql_part = '';
		foreach($join as $joinItem){
			$sql_part .= $joinItem ? "JOIN $joinItem" . PHP_EOL : '';
		}
		static::$join = rtrim($sql_part ? PHP_EOL . $sql_part : '', PHP_EOL);
		return static::class;
	}

	protected static function where($where = ''){
		static::$where = $where || static::$where ? PHP_EOL . "WHERE " . ($where ?: static::$where) : static::$where;
		return static::class;
	}

	protected static function group($group = ''){
		static::$group = $group || static::$group ? PHP_EOL . "GROUP BY " . ($group ?: static::$group) : static::$group;
		return static::class;
	}

	protected static function order($order = '', $sort = ''){
		$order = explode(', ', $order ?: static::$order);
		$sql_part = '';
		foreach($order as $orderItem){
			$sql_part .= $orderItem ? "$orderItem " . static::$sort . ', ' : '';
		}
		static::$order = rtrim($sql_part ? PHP_EOL . "ORDER BY $sql_part" : static::$order, ', ');
		return static::class;
	}

	protected static function limit($limit = ''){
		static::$limit = $limit || static::$limit ? PHP_EOL . "LIMIT " . ($limit ?: static::$limit) : static::$limit;
		return static::class;
	}

	protected static function update_sql($update = ''){
		static::$update = "UPDATE `" . ($update ?: (static::$update ?: self::getTabelName())) . '`';
		return static::class;
	}

	protected static function set($set = ''){
		static::$set = PHP_EOL . "SET " . ($set ?: static::$set);
		return static::class;
	}

	protected static function insert($insert = ''){
		static::$insert = "INSERT INTO `" . ($insert ?: (static::$insert ?: self::getTabelName())) . '`';
		return static::class;
	}

	protected static function into($into = ''){
		static::$into = PHP_EOL . "(" . ($into ?: static::$into) . ")";
		return static::class;
	}

	protected static function values($values = ''){
		static::$values = PHP_EOL . "VALUES " . ($values ?: static::$values);
		return static::class;
	}

	protected static function delete_sql($delete = ''){
		static::$delete = "DELETE FROM `" . ($delete ?: (static::$delete ?: self::getTabelName())) . '`';
		return static::class;
	}

}
