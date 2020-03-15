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
		list($condition, $callback, $isClosure) = $this->setArgs($condition, $callback);
		// если в свойствах класс существует ключ из переданных настроек, то заполняем данное свойство переданным значением
		foreach($options as $k => $v){
			// проверяем существет ли такое свойство у класса
            //debug([$k => $v, 'isClosure' => $isClosure, 'condition' => $isClosure ? $condition($k) : $condition], 'property_exists' => property_exists($this, $k));
			if($isClosure ? $condition($k) : $condition){
				$this->$k = $v;
				$callback($k, $v);
			}
		}
        //debug($this);
	}

	protected function setArgs($condition, $callback){
        $base_condition = function($k){ return property_exists($this, $k); };
		$isClosure = $this->isClosure($condition);
        //$noCallback = $callback === false;
        $callback2 = null;
        $callback2 = function(){};
        //$callback2 = false;
        $noCallback = $callback2 === false;
        $reverse = $isClosure;
        $reverse2 = $isClosure && !$noCallback;
        debug(['class' => get_class($this), 'isClosure' => $isClosure, 'noCallback' => $noCallback, 'reverse' => $reverse2]);
        if($reverse){
            $callback = $isClosure ? $condition : $callback;
            $condition = $isClosure ? $base_condition : $condition;
        }
		return [$condition ?? $base_condition, $callback ?? function(){}, $isClosure];
	}

}
