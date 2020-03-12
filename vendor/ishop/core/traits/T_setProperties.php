<?php
// трейт для создания единственного экземляра класса (если он уже создан обращение идет к уже созданному экземпляру)
// трейт (trait) (кусок кода, который мы можем копипастить в другие файлы)
// реализаванный в трейте код вставляется в месте, где он задействован (подключен)

namespace ishop\traits;

use \Closure;

trait T_SetProperties {

	use T_Closure;

	// получает опции
	protected function setProperties($options, $condition = null, Closure $callback = null){
		list($condition, $callback) = $this->setArgs($condition, $callback);
		// если в свойствах класс существует ключ из переданных настроек, то заполняем данное свойство переданным значением
		foreach($options as $k => $v){
			// проверяем существет ли такое свойство у класса
			if($this->isClosure($condition) ? $condition($k) : $condition){
				$this->$k = $v;
				$callback($k, $v);
			}
		}
	}

	protected function setArgs($condition, $callback){
		$isClosure = $this->isClosure($condition);
		$base_condition = function($k){ return property_exists($this, $k); };
		$callback = $isClosure ? $condition : ($callback ?? function(){});
		$condition = $isClosure ? $base_condition : ($condition ?? function(){ return true; });
		return [$condition, $callback];
	}

}
