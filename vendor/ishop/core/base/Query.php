<?php

namespace ishop\base;

abstract class Query {

	protected $table2 = null;

	protected static $table = null;
	protected static $class = null;
	protected static $model = null;

	protected static $models = [];

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

	protected static function init(){
		self::$class = self::$class == static::class ? self::$class : static::class;
		if(!array_key_exists(static::$class, static::$models)){
			self::$models[static::$class] = new static::$class;
		}
		return self::getModel();
	}

	protected static function getModel(){
		return self::$model = self::$model instanceof self::$class ? self::$model : self::$models[self::$class];
	}

	protected static function getTable(){
		self::init()->table2 = self::init()->table2 ?? self::getTableName();
		return self::init()::$table = self::$table == self::getTableName() ? self::$table : self::getTableName();
	}

	// возвращает имя таблицы в БД на основе имени модели (thisMethodName => this_method_name)
	public static function getTableName($class = null){
		return lowerCamelCase(getClassShortName($class ?? get_called_class()));
	}

	protected static function getSql(){
		self::init();
		self::$query = '';
		if(static::$select){
			self::select()::from()::join()::where()::group()::order()::limit();
		}
		elseif(static::$set){
			self::update_sql()::set()::where();
		}
		elseif(static::$into && static::$values){
			self::insert()::into()::values();
		}
		elseif(static::$delete){
			self::delete_sql()::where();
		}
		debug(self::$query);
		debug(self::$class);
		return self::$query;
	}

	protected static function select($select = null){
		self::$query .= static::$select = "SELECT " . ($select ?: static::$select);
		return static::class;
	}

	protected static function from($from = ''){
		self::$query .= static::$from = PHP_EOL . "FROM `" . ($from ?: (static::$from ?: self::getTable())) . '`';
		return static::class;
	}

	protected static function join($join = ''){
		$join = explode(', ', $join ?: static::$join);
		$sql_part = '';
		foreach($join as $joinItem){
			$sql_part .= $joinItem ? "JOIN $joinItem" . PHP_EOL : '';
		}
		self::$query .= static::$join = rtrim($sql_part ? PHP_EOL . $sql_part : '', PHP_EOL);
		return static::class;
	}

	protected static function where($where = ''){
		self::$query .= static::$where = $where || static::$where ? PHP_EOL . "WHERE " . ($where ?: static::$where) : static::$where;
		return static::class;
	}

	protected static function group($group = ''){
		self::$query .= static::$group = $group || static::$group ? PHP_EOL . "GROUP BY " . ($group ?: static::$group) : static::$group;
		return static::class;
	}

	protected static function order($order = '', $sort = ''){
		$order = explode(', ', $order ?: static::$order);
		$sql_part = '';
		foreach($order as $orderItem){
			$sql_part .= $orderItem ? "$orderItem " . static::$sort . ', ' : '';
		}
		self::$query .= static::$order = rtrim($sql_part ? PHP_EOL . "ORDER BY $sql_part" : static::$order, ', ');
		return static::class;
	}

	protected static function limit($limit = ''){
		self::$query .= static::$limit = $limit || static::$limit ? PHP_EOL . "LIMIT " . ($limit ?: static::$limit) : static::$limit;
		return static::class;
	}

	protected static function update_sql($update = ''){
		self::$query .= static::$update = "UPDATE `" . ($update ?: (static::$update ?: self::getTable())) . '`';
		return static::class;
	}

	protected static function set($set = ''){
		self::$query .= static::$set = PHP_EOL . "SET " . ($set ?: static::$set);
		return static::class;
	}

	protected static function insert($insert = ''){
		self::$query .= static::$insert = "INSERT INTO `" . ($insert ?: (static::$insert ?: self::getTable())) . '`';
		return static::class;
	}

	protected static function into($into = ''){
		self::$query .= static::$into = PHP_EOL . "(" . ($into ?: static::$into) . ")";
		return static::class;
	}

	protected static function values($values = ''){
		self::$query .= static::$values = PHP_EOL . "VALUES " . ($values ?: static::$values);
		return static::class;
	}

	protected static function delete_sql($delete = ''){
		self::$query .= static::$delete = "DELETE FROM `" . ($delete ?: (static::$delete ?: self::getTable())) . '`';
		return static::class;
	}

}
