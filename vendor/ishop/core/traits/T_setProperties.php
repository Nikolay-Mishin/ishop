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

		$this->reverse([
			[
				'type' => 'true && null',
				'condition' => true,
				'callback' => null
			],
			[
				'type' => 'true && function()',
				'condition' => true,
				'callback' => function(){}
			],
			[
				'type' => 'true && false',
				'condition' => true,
				'callback' => false
			],
			[
				'type' => 'function() && null',
				'condition' => function(){},
				'callback' => null
			],
			[
				'type' => 'function() && function(){}',
				'condition' => function(){},
				'callback' => function(){}
			],
			[
				'type' => 'function() && false',
				'condition' => function(){},
				'callback' => false
			]
		]);

		$isClosure = $this->isClosure($condition);
		$reverse = $isClosure;
		//debug(['class' => get_class($this), 'isClosure' => $isClosure, 'noCallback' => $noCallback, 'reverse' => $reverse]);
		if($reverse){
			$callback = $isClosure ? $condition : $callback;
			$condition = $isClosure ? $base_condition : $condition;
		}
		return [$condition ?? $base_condition, $callback ?? function(){}, $isClosure];
	}

	private function reverse($array){
		foreach($array as $item){
			$isClosure = $this->isClosure($item['condition']);
			$noCallback = !$this->isClosure($item['callback']) || $item['callback'] === false;
			$reverse = $isClosure && $noCallback;
			debug(['type' => $item['type'], 'isClosure' => $isClosure, 'callback' => $this->isClosure($item['callback']), 'noCallback' => $noCallback, 'reverse' => $reverse]);
		}
	}

}
