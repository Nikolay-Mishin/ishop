<?php
// трейт для создания единственного экземляра класса (если он уже создан обращение идет к уже созданному экземпляру)
// трейт (trait) (кусок кода, который мы можем копипастить в другие файлы)
// реализаванный в трейте код вставляется в месте, где он задействован (подключен)

namespace ishop\traits;

trait T_SetProperties {

	use T_Closure;

	// получает опции
	protected function setProperties(array $options, ...$args): void {
		if (count($args) > 2) throw new Exception("Число аргументов в методе $this"."->setProperties() не должно превышать 3", 500);
		$base_callback = function($k, $v){};
		$base_condition = function($k) { return property_exists($this, $k); };

		$options = isObject(current($options)) ? current($options) : $options;
		$callback = $args[0] ?? $base_callback;
		$condition = !isBool($callback) && $callback && ($args[1] ?? null) ? $arg_3 : $base_condition;
		if (isBool($callback)) {
			$condition = $callback;
			$callback = $base_callback;
		}
		
		// если в свойствах класс существует ключ из переданных настроек, то заполняем данное свойство переданным значением
		foreach ($options as $k => $v) {
			// проверяем существет ли такое свойство у класса
			if (!isBool($condition) && $this->isClosure($condition) ? $condition($k) : $condition) {
				$this->$k = $v;
				$callback($k, $v);
			}
		}
	}

}
