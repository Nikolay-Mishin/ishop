<?php
// трейт для создания единственного экземляра класса (если он уже создан обращение идет к уже созданному экземпляру)
// трейт (trait) (кусок кода, который мы можем копипастить в другие файлы)
// реализаванный в трейте код вставляется в месте, где он задействован (подключен)

namespace ishop\traits;

use \Closure;

trait T_SetProperties {

	use T_Closure;

	// получает опции
	protected function setProperties(object $options, ?bool $condition = null, ?Closure $callback = null): void {
		list($condition, $callback, $isClosure) = $this->setArgs($condition, $callback);
		// если в свойствах класс существует ключ из переданных настроек, то заполняем данное свойство переданным значением
		foreach ($options as $k => $v) {
			// проверяем существет ли такое свойство у класса
			if ($isClosure ? $condition($k) : $condition) {
				$this->$k = $v;
				$callback($k, $v);
			}
		}
		//debug($this);
	}

	protected function setArgs(bool $condition, ?Closure $callback): array { 
		$base_condition = function($k) { return property_exists($this, $k); };
		$isClosure = $this->isClosure($condition);
		$reverse = $isClosure && !$this->isClosure($callback) && $callback !== false;
		$callback = $reverse ? $condition : ($callback ?? function(){});
		$condition = $reverse ? $base_condition : ($condition ?? $base_condition);
		$isClosure = $this->isClosure($condition);
		return [$condition, $callback, $this->isClosure($condition)];
	}

}
