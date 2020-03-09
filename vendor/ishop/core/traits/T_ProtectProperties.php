<?php
// трейт для создания единственного экземляра класса (если он уже создан обращение идет к уже созданному экземпляру)
// трейт (trait) (кусок кода, который мы можем копипастить в другие файлы)
// реализаванный в трейте код вставляется в месте, где он задействован (подключен)

namespace ishop\traits;

trait T_ProtectProperties {

	protected $returnProtect = [];

	public function getReturnProtect(){
		return $this->returnProtect;
	}

	protected function setReturnProtect($properties){
		foreach(toArray($properties) as $property => $mod){
			if(!array_key_exists($property, $this->returnProtect)){
				$isConst = gettype($property) == 'integer';
				list($property, $mod) = [$isConst ? $mod : $property, !$isConst && $mod == 'set' ? 'set' : 'const'];
				$this->returnProtect[$property] = $mod;
			}
		}
	}

	public function __get($property){
		return $this->propertyExist($this, $property, function($obj, $property){
			return $obj->$property;
		});
	}

	public function __set($property, $value){
		$this->propertyExist($this, $property, function($obj, $property, $const) use ($value) {
			if(!$const) $obj->$property = $value;
		});
	}

	protected function propertyExist($obj, $property, $callback = null, $condition = null){
		debug([$property, $obj->$property, $obj]);
		$key_exist = array_key_exists($property, $this->returnProtect);
		$condition = $callback === true ? $callback : ($condition === true ?: $key_exist);
		$callback = in_array($callback, [null, true], true) ? function(){} : $callback;
		debug([$condition]);
		if(property_exists($obj, $property) && $condition){
			return $callback($obj, $property, $this->returnProtect[$property] == 'const');
		}elseif($condition){
			foreach($this->returnProtect as $protect => $mod){
				debug([$protect => $mod, $property]);
				//if(property_exists($this->$protect, $property)){
				//    return $callback($obj, $property, $this->returnProtect[$property] == 'const');
				//}
				$this->propertyExist($this->$protect, $property, function($obj, $property){
					return $obj->$property;
				}, true);
			}
		}
	}

}
