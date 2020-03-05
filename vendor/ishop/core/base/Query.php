<?php

namespace ishop\base;

abstract class Query {

	protected $table = null;

	protected static $class = null;
	protected static $model = null;

	protected static $models = [];

	protected static $query = '';

	protected $from = '';
	protected $select = '';
	protected $join = '';
	protected $where = '';
	protected $group = '';
	protected $sort = '';
	protected $order = '';
	protected $limit = '';

	protected $update = '';
	protected $set = '';

	protected $insert = '';
	protected $into = '';
	protected $values = '';

	protected $delete = '';

	protected static function init(){
		self::$class = self::$class == static::class ? self::$class : static::class;
		if(!array_key_exists(self::$class, self::$models)){
			self::$models[self::$class] = new self::$class;
		}
		return self::getModel();
	}

	protected static function getModel(){
		return self::$model = self::$model instanceof self::$class ? self::$model : self::$models[self::$class];
	}

	protected static function getTable(){
		return self::init()->table = self::init()->table ?? self::getTableName();
	}

	// возвращает имя таблицы в БД на основе имени модели (thisMethodName => this_method_name)
	public static function getTableName($class = null){
		return lowerCamelCase(getClassShortName($class ?? get_called_class()));
	}

	protected static function getSql(){
		self::init();
		self::$query = '';
		if(self::init()->select){
			self::init()->select()->from()->join()->where()->group()->order()->limit();
		}
		elseif(self::init()->set){
			self::init()->update_sql()->set()->where();
		}
		elseif(self::init()->into && self::init()->values){
			self::init()->insert()->into()->values();
		}
		elseif(self::init()->delete){
			self::init()->delete_sql()->where();
		}
		return self::$query;
	}

	protected function select($select = null){
		self::$query .= $this->select = "SELECT " . ($select ?: $this->select);
		return $this;
	}

	protected function from($from = ''){
		self::$query .= $this->from = PHP_EOL . "FROM `" . ($from ?: ($this->from ?: self::getTable())) . '`';
		return $this;
	}

	protected function join($join = ''){
		$join = explode(', ', $join ?: $this->join);
		$sql_part = '';
		foreach($join as $joinItem){
			$sql_part .= $joinItem ? "JOIN $joinItem" . PHP_EOL : '';
		}
		self::$query .= $this->join = rtrim($sql_part ? PHP_EOL . $sql_part : '', PHP_EOL);
		return $this;
	}

	protected function where($where = ''){
		self::$query .= $this->where = $where || $this->where ? PHP_EOL . "WHERE " . ($where ?: $this->where) : $this->where;
		return $this;
	}

	protected function group($group = ''){
		self::$query .= $this->group = $group || $this->group ? PHP_EOL . "GROUP BY " . ($group ?: $this->group) : $this->group;
		return $this;
	}

	protected function order($order = '', $sort = ''){
		$order = explode(', ', $order ?: $this->order);
		$sql_part = '';
		foreach($order as $orderItem){
			$sql_part .= $orderItem ? "$orderItem " . $this->sort . ', ' : '';
		}
		self::$query .= $this->order = rtrim($sql_part ? PHP_EOL . "ORDER BY $sql_part" : $this->order, ', ');
		return $this;
	}

	protected function limit($limit = ''){
		self::$query .= $this->limit = $limit || $this->limit ? PHP_EOL . "LIMIT " . ($limit ?: $this->limit) : $this->limit;
		return $this;
	}

	protected function update_sql($update = ''){
		self::$query .= $this->update = "UPDATE `" . ($update ?: ($this->update ?: self::getTable())) . '`';
		return $this;
	}

	protected function set($set = ''){
		self::$query .= $this->set = PHP_EOL . "SET " . ($set ?: $this->set);
		return $this;
	}

	protected function insert($insert = ''){
		self::$query .= $this->insert = "INSERT INTO `" . ($insert ?: ($this->insert ?: self::getTable())) . '`';
		return $this;
	}

	protected function into($into = ''){
		self::$query .= $this->into = PHP_EOL . "(" . ($into ?: $this->into) . ")";
		return $this;
	}

	protected function values($values = ''){
		self::$query .= $this->values = PHP_EOL . "VALUES " . ($values ?: $this->values);
		return $this;
	}

	protected function delete_sql($delete = ''){
		self::$query .= $this->delete = "DELETE FROM `" . ($delete ?: ($this->delete ?: self::getTable())) . '`';
		return $this;
	}

}
